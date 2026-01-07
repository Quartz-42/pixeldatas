# PixelDatas

**PixelDatas** est un site web dédié aux Pokémons qui permet de retrouver toutes les informations sur les Pokémons de toutes les générations

## 🎯 Fonctionnalités

- **Catalogue complet** : Parcourez tous les Pokémons avec leurs informations détaillées
- **Recherche avancée** : Recherchez par nom, génération ou type de Pokémon
- **Fiches détaillées** : Consultez les statistiques, évolutions, types et sprites de chaque Pokémon
- **Filtrage par génération** : Explorez les Pokémons par génération (1 à 9)
- **Filtrage par type** : Découvrez les Pokémons selon leurs types (Feu, Eau, Plante, etc.)
- **Graphiques de statistiques** : Visualisez les stats de chaque Pokémon sous forme de graphique
- **Comparaison de Pokemons** : Comparez jusqu'à 4 Pokémons : génération, type, statistiques...
- **Évolutions** : Consultez les chaînes d'évolution, pré-évolutions et méga-évolutions
- **Sprites multiples** : Affichez les formes normales, shiny, Gmax et Gmax shiny
- **Interface responsive** : Navigation optimisée sur tous les appareils

## 🛠️ Technologies utilisées

- **Framework** : Symfony 7.3-->8
- **Langage** : PHP 8.4
- **Frontend** : Twig, TailwindCSS, Stimulus
- **Base de données** : MySQL
- **Graphiques** : Chart.js
- **Pagination** : Pagerfanta
- **Navigation** : Turbo (Hotwired)
- **Conteneurisation** : Docker

## 📱 Utilisation

### Page d'accueil

- Affichage de 3 Pokémons aléatoires
- Lien vers le catalogue complet

### Catalogue des Pokémons

- Liste paginée de tous les Pokémons
- Recherche par nom
- Filtres par génération et type
- Cards avec informations essentielles (nom, numéro Pokédex, types, génération)

### Fiche détaillée d'un Pokémon

- Statistiques complètes (PV, Attaque, Défense, etc.)
- Graphique des statistiques
- Informations sur les évolutions
- Sprites (normal, shiny, Gmax)
- Types et talents

### Navigation par filtres

- **Par génération** : `/pokemons/generation/{1-9}`
- **Par type** : `/pokemons/type/{type}`

## 🎮 Source des données

Les données des Pokémons sont récupérées via une API externe et stockées en base de données pour optimiser les performances
