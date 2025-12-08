# TODO: Implémenter le téléchargement de médias depuis URLs

- [ ] Modifier la vue resources/views/media/create.blade.php pour ajouter des boutons radio pour choisir entre fichier local ou URL
- [ ] Ajouter un champ texte pour l'URL quand l'option URL est sélectionnée
- [ ] Modifier la méthode store dans app/Http/Controllers/MediaController.php pour gérer les téléchargements depuis URL
- [ ] Ajouter validation pour les URLs
- [ ] Utiliser Http::get() pour télécharger le contenu depuis l'URL
- [ ] Valider le type de contenu téléchargé (image, vidéo, etc.)
- [ ] Stocker le fichier téléchargé dans storage/app/public/medias
- [ ] Gérer les erreurs (URL invalide, réseau, etc.)
- [ ] Tester la fonctionnalité avec une URL d'exemple
