<?php

// Fichier : app/Helpers/SoapHelpers.php

if (!function_exists('getListeCaissesAvecSoapClient')) {
    /**
     * Récupère la liste des caisses depuis le service SOAP externe.
     *
     * @param string $code_societe Le code de la société pour l'appel API.
     * @param string $connected_user Le nom d'utilisateur pour filtrer les caisses.
     * @return array Retourne un tableau d'objets caisse, un tableau vide si aucune n'est trouvée,
     *               ou un tableau avec une clé "Erreur SOAP" en cas d'échec de la connexion.
     */
    function getListeCaissesAvecSoapClient(string $code_societe, string $connected_user): array
    {
        try {
            $serviceUrl = "http://45.155.249.99/WS_YODIGEST_ERP_WEB/awws/WS_YODIGEST_ERP.awws?wsdl";
            $options = [
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => 1, // Permet un débogage plus facile
            ];

            // SoapClient est une classe native de PHP, pas besoin de l'importer avec 'use'
            $service = new SoapClient($serviceUrl, $options);

            $resultat = $service->Mobile_list_caisse($code_societe, $connected_user);

            if (property_exists($resultat, 'Mobile_list_caisseResult')) {
                $caisses = $resultat->Mobile_list_caisseResult;

                // Si le service retourne un seul objet, on le met dans un tableau pour une gestion cohérente
                if (is_object($caisses)) {
                    return [$caisses];
                }
                
                // Si c'est déjà un tableau, on le retourne directement
                if (is_array($caisses)) {
                    return $caisses;
                }
            }
            
            // Si la propriété n'existe pas ou si le format est inattendu, retourner un tableau vide.
            return [];

        } catch (SoapFault $e) { // SoapFault est aussi une classe native
            // En production, il est préférable de logger l'erreur plutôt que de la retourner à l'utilisateur
            // Log::error("Erreur SOAP dans getListeCaissesAvecSoapClient: " . $e->getMessage());
            return ["Erreur SOAP" => $e->getMessage()];
        }
    }
}


if (!function_exists('getMouvementsCaisse')) {
    /**
     * Récupère les mouvements d'une caisse spécifique sur une période donnée via SOAP.
     *
     * @param string $id_caisse L'identifiant unique de la caisse.
     * @param string $date_debut La date de début au format 'Ymd' (ex: '20250101').
     * @param string $date_fin La date de fin au format 'Ymd' (ex: '20251231').
     * @return array Retourne un tableau d'objets mouvement, un tableau vide si aucun n'est trouvé,
     *               ou un tableau avec une clé "Erreur SOAP" en cas d'échec de la connexion.
     */
    function getMouvementsCaisse(string $id_caisse, string $date_debut, string $date_fin): array
    {
        try {
            $serviceUrl = "http://45.155.249.99/WS_YODIGEST_ERP_WEB/awws/WS_YODIGEST_ERP.awws?wsdl";
            $options = [
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => 1,
            ];

            $service = new SoapClient($serviceUrl, $options);

            $resultat = $service->Mobile_mouvement_caisse($id_caisse, $date_debut, $date_fin);

            if (property_exists($resultat, 'Mobile_mouvement_caisseResult')) {
                $mouvements = $resultat->Mobile_mouvement_caisseResult;

                // Si le service retourne un seul objet, on le met dans un tableau
                if (is_object($mouvements)) {
                    return [$mouvements];
                }

                if(is_array($mouvements)) {
                    return $mouvements;
                }
            }

            return [];

        } catch (SoapFault $e) {
            // Log::error("Erreur SOAP dans getMouvementsCaisse: " . $e->getMessage());
            return ["Erreur SOAP" => $e->getMessage()];
        }
    }
}