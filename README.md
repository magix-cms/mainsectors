# Mainsectors
Plugin Mainsectors for Magix CMS 3

Ajouter une ou plusieurs page / catégorie sur la page d'accueil de votre site internet.

## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner mainsectors (Secteurs principaux sur la page d'accueil).
 * Une fois dans le plugin, laisser faire l'auto installation
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.
 * Copier le contenu du dossier **skin/public** dans le dossier de votre skin.

### Ajouter dans home.tpl la ligne suivante

```smarty
{block name="main:after"}
    {include file="mainsectors/brick/sectors.tpl"}
{/block}
````