# Camagru

## Résumé
Le but de ce projet est de construire une application web.

Version : 4

## Contenu

2. Introduction
3. Objectifs
4. Instructions générales
5. Partie obligatoire
   - V.1 Caractéristiques communes
   - V.2 Fonctionnalités utilisateur
   - V.3 Fonctionnalités de la galerie
   - V.4 Fonctionnalités d'édition
   - V.5 Contraintes et obligations
6. Partie bonus
7. Soumission et évaluation par les pairs

## Chapitre II : Introduction

Maintenant, vous êtes prêts à créer vos premières applications web, comme des pros. Si cela ne vous dérange pas, le web est un monde vaste et riche, vous permettant de diffuser rapidement des données et du contenu à tout le monde autour du globe.

Maintenant que vous connaissez les bases, il est temps de laisser ces vieilles listes de tâches et sites eBusiness et de vous envoler vers de plus grands projets.

Il est également temps pour vous de découvrir de nouvelles notions et la beauté de :

- Design réactif
- Manipulation du DOM
- Débogage SQL
- Contrefaçon de requête intersites (CSRF)
- Partage de ressources entre origines multiples (CORS)
- ...

---

## Chapitre III : Objectifs

Ce projet web vous met au défi de créer une petite application web vous permettant de faire des éditions de photos et de vidéos de base en utilisant votre webcam et des images prédéfinies.

Évidemment, ces images doivent avoir un canal alpha, sinon votre superposition n'aurait pas l'effet escompté !

Nous allons, par exemple, capturer le moment précis d'un lancement de chat intergalactique, voici la preuve :

Les utilisateurs de l'application doivent pouvoir sélectionner une image dans une liste d'images superposables (par exemple un cadre photo, ou d'autres objets "nous ne voulons pas savoir à quoi vous les utilisez"), prendre une photo avec sa webcam et admirer le résultat qui devrait mélanger les deux images.

Toutes les images capturées doivent être publiques, aimables et commentables.

---

## Chapitre IV : Instructions générales

- Ce projet sera corrigé uniquement par des humains. Vous êtes autorisé à organiser et nommer vos fichiers comme bon vous semble, mais vous devez suivre les règles suivantes.

- Votre application web ne doit produire aucune erreur, aucun avertissement ou ligne de journal dans aucune console, côté serveur et côté client. Néanmoins, en raison du manque de HTTPS, toute erreur liée à getUserMedia() est tolérée.

- Vous êtes libre d'utiliser n'importe quel langage pour créer votre application côté serveur, mais, pour chaque fonction que vous utilisez, vous devez vérifier qu'un équivalent existe dans la bibliothèque standard PHP.

- Côté client, vos pages doivent utiliser HTML, CSS et JavaScript.

- La conteneurisation à jour est indispensable.

- N'oubliez pas que certains choix peuvent vous rendre plus attractif sur le marché du travail.

- Tous les frameworks, micro-frameworks ou bibliothèques que vous ne créez pas et sans équivalent dans la bibliothèque standard PHP sont totalement interdits, sauf pour les frameworks CSS qui n'ont pas besoin de JavaScript interdit.

- Votre application doit être exempte de toute fuite de sécurité. Vous devez au moins gérer les cas mentionnés dans la partie obligatoire. Néanmoins, vous êtes encouragés à approfondir la sécurité de votre application, pensez à la confidentialité de vos données !

- Vous êtes libre d'utiliser n'importe quel serveur web, comme Apache, Nginx ou même le serveur web intégré.

- Votre application web doit être au moins compatible avec Firefox (>= 41) et Chrome (>= 46).

Pour des raisons évidentes de sécurité, toutes les informations d'identification, clés API, variables d'environnement, etc. doivent être enregistrées localement dans un fichier `.env` et ignorées par git. Les informations d'identification stockées publiquement entraîneront directement l'échec du projet.

---

## Chapitre V : Partie obligatoire

### V.1 Caractéristiques communes
Vous développerez une application web. Même si ce n'est pas obligatoire, essayez de structurer votre application (comme une application MVC, par exemple).

Votre site web doit avoir une mise en page décente (c'est-à-dire au moins un en-tête, une section principale et un pied de page), être capable de s'afficher correctement sur les appareils mobiles et avoir une mise en page adaptée aux petites résolutions.

Tous vos formulaires doivent avoir des validations correctes et l'ensemble du site doit être sécurisé. Ce point est OBLIGATOIRE et sera vérifié lors de l'évaluation de votre application. Pour avoir une idée, voici des éléments qui NE sont PAS considérés comme SÉCURISÉS :

- Stocker des mots de passe en clair ou non chiffrés dans la base de données.
- Offrir la possibilité d'injecter du HTML ou du JavaScript "utilisateur" dans des variables mal protégées.
- Offrir la possibilité de télécharger du contenu non souhaité sur le serveur.
- Offrir la possibilité de modifier une requête SQL.
- Utiliser un formulaire externe pour manipuler des données prétendument privées.

### V.2 Fonctionnalités utilisateur
- L'application doit permettre à un utilisateur de s'inscrire en demandant au moins une adresse email valide, un nom d'utilisateur et un mot de passe avec un niveau de complexité minimal.

- À la fin du processus d'inscription, un utilisateur doit confirmer son compte via un lien unique envoyé à l'adresse email renseignée dans le formulaire d'inscription.

- L'utilisateur doit ensuite pouvoir se connecter à votre application, en utilisant son nom d'utilisateur et son mot de passe. Il doit également pouvoir demander à l'application d'envoyer un mail de réinitialisation de mot de passe, s'il oublie son mot de passe.

- L'utilisateur doit pouvoir se déconnecter en un clic à tout moment sur n'importe quelle page.

- Une fois connecté, un utilisateur doit pouvoir modifier son nom d'utilisateur, son adresse mail ou son mot de passe.

### V.3 Fonctionnalités de la galerie
- Cette partie doit être publique et doit afficher toutes les images éditées par tous les utilisateurs, classées par date de création. Elle doit également permettre (seulement) à un utilisateur connecté de les aimer et/ou de les commenter.

- Lorsqu'une image reçoit un nouveau commentaire, l'auteur de l'image doit être notifié par email. Cette préférence doit être activée par défaut mais peut être désactivée dans les préférences de l'utilisateur.

- La liste des images doit être paginée, avec au moins 5 éléments par page.

### V.4 Fonctionnalités d'édition

**Figure V.1 : Juste une idée de mise en page pour la page d'édition**

Cette partie doit être accessible uniquement aux utilisateurs authentifiés/connectés et doit rejeter gentiment tous les autres utilisateurs qui tentent d'y accéder sans être connectés avec succès.

Cette page doit contenir 2 sections :

- Une section principale contenant l'aperçu de la webcam de l'utilisateur, la liste des images superposables et un bouton permettant de capturer une photo.

- Une section latérale affichant des vignettes de toutes les photos précédemment prises.

La mise en page de votre page doit normalement ressembler à la Figure V.1.

- Les images superposables doivent être sélectionnables et le bouton permettant de prendre la photo doit être inactif (non cliquable) tant qu'aucune image superposable n'a été sélectionnée.

- La création de l'image finale (donc, entre autres, la superposition des deux images) doit être faite côté serveur.

- Parce que tout le monde n'a pas une webcam, vous devez permettre le téléchargement d'une image utilisateur au lieu de capturer une avec la webcam.

- L'utilisateur doit pouvoir supprimer ses images éditées, mais seulement les siennes, pas celles des autres utilisateurs.

### V.5 Contraintes et obligations
Pour résumer, votre application doit respecter les choix technologiques suivants :

- Langages autorisés :

  - [Serveur] N'importe lequel (limité à la bibliothèque standard PHP)

  - [Client] HTML - CSS - JavaScript (uniquement avec les API natives
du navigateur)

- Frameworks autorisés :

  - [Serveur] N'importe lequel (jusqu'à la bibliothèque standard PHP)

  - [Client] Frameworks CSS tolérés, sauf s'ils ajoutent du JavaScript interdit.

Votre projet doit impérativement contenir :

- Un (ou plusieurs) conteneur pour déployer votre site avec une commande. Tout ce qui est équivalent à docker-compose est acceptable.

---

## Chapitre VI : Partie bonus

Si la partie requise est entièrement et parfaitement réalisée, vous pouvez ajouter tous les bonus que vous souhaitez ; Ils seront évalués par vos examinateurs. Vous devez cependant toujours respecter les exigences dans les parties bonus (c'est-à-dire que le traitement des images doit être effectué côté serveur).

Si vous manquez d'inspiration, voici quelques pistes :

- "AJAXify" les échanges avec le serveur.

- Proposez un aperçu en direct du résultat édité, directement sur l'aperçu de la webcam. Nous devons noter que cela est beaucoup plus facile qu'il n'y paraît.

- Faites une pagination infinie de la partie galerie du site.

- Offrez la possibilité à un utilisateur de partager ses images sur les réseaux sociaux.

- Rendre un GIF animé.

La partie bonus ne sera évaluée que si la partie obligatoire est PARFAITE. Parfaite signifie que la partie obligatoire a été intégralement réalisée et fonctionne sans dysfonctionnement. Si vous n'avez pas rempli TOUTES les exigences obligatoires, votre partie bonus ne sera pas du tout évaluée.

---

## Chapitre VII : Soumission et évaluation par les pairs

Remettez votre devoir dans votre dépôt Git comme d'habitude. Seul le travail à l'intérieur de votre dépôt sera évalué pendant la défense. N'hésitez pas à vérifier deux fois les noms de vos dossiers et fichiers pour vous assurer qu'ils sont corrects.