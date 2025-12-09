<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeContenu;
use App\Models\Contenu;
use App\Models\Region;
use App\Models\Langue;
use App\Models\Utilisateur;
use Carbon\Carbon;

class CompleteContentSeeder extends Seeder
{
    /**
     * Seed des contenus de tous les types
     */
    public function run(): void
    {
        // Récupérer les données nécessaires
        $regions = Region::all();
        $langues = Langue::all();
        $typesContenus = TypeContenu::all();
        
        // Trouver un auteur - méthode plus robuste avec join
        $roleAuteur = \App\Models\Role::where('nom_role', 'Auteur')->first();
        $auteurs = collect();
        
        if ($roleAuteur) {
            $auteurs = Utilisateur::where('id_role', $roleAuteur->id)->get();
        }
        
        if ($auteurs->isEmpty()) {
            // Si pas d'auteur, utiliser le premier utilisateur admin
            $roleAdmin = \App\Models\Role::where('nom_role', 'Admin')->first();
            if ($roleAdmin) {
                $auteurs = Utilisateur::where('id_role', $roleAdmin->id)->take(1)->get();
            }
        }
        
        if ($auteurs->isEmpty()) {
            // Dernière tentative : utiliser n'importe quel utilisateur actif
            $auteurs = Utilisateur::where('statut', 'actif')->take(1)->get();
        }
        
        if ($auteurs->isEmpty()) {
            $this->command->error('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs avec UsersPerRoleSeeder.');
            return;
        }
        
        $auteur = $auteurs->first();
        $region = $regions->first() ?? Region::first();
        $langue = $langues->first() ?? Langue::first();
        
        if (!$region || !$langue) {
            $this->command->error('Région ou langue manquante. Vérifiez RegionSeeder et LangueSeeder.');
            return;
        }
        
        // Contenus par type
        $contenus = [
            // ARTICLE
            [
                'type' => 'Article',
                'titre' => 'La Culture Béninoise : Un Patrimoine Riche et Diversifié',
                'texte' => '<h2>Introduction</h2><p>Le Bénin, petit pays d\'Afrique de l\'Ouest, possède une culture exceptionnellement riche et diversifiée. Cette culture s\'exprime à travers de multiples facettes : traditions orales, arts, musiques, danses, et bien plus encore.</p><h2>Les Traditions Orales</h2><p>Les traditions orales constituent le pilier de la culture béninoise. Les contes, légendes et proverbes sont transmis de génération en génération, préservant ainsi la sagesse ancestrale.</p><h2>L\'Art et l\'Artisanat</h2><p>L\'artisanat béninois est reconnu mondialement, notamment pour ses sculptures en bois, ses tissus traditionnels et ses bijoux en bronze.</p><h2>Conclusion</h2><p>La culture béninoise est un trésor à préserver et à partager avec le monde entier.</p>',
                'est_premium' => false,
                'prix' => null
            ],
            
            // HISTOIRE / LÉGENDE
            [
                'type' => 'Histoire / Légende',
                'titre' => 'La Légende de la Reine Tassi Hangbé',
                'texte' => '<h2>La Reine Tassi Hangbé</h2><p>Il était une fois, dans le royaume du Dahomey, une reine légendaire nommée Tassi Hangbé. Elle régna avec sagesse et courage, défendant son peuple contre les envahisseurs.</p><p>La légende raconte qu\'elle possédait des pouvoirs mystiques lui permettant de communiquer avec les esprits ancestraux. Elle utilisait ces pouvoirs pour guider son peuple vers la prospérité.</p><p>Son règne fut marqué par de nombreuses victoires et par l\'établissement de lois justes qui régirent le royaume pendant des générations.</p><p>Aujourd\'hui encore, son nom est vénéré et ses enseignements continuent d\'inspirer les Béninois.</p>',
                'est_premium' => true,
                'prix' => 2500
            ],
            
            // CONTE / FABLE
            [
                'type' => 'Conte / Fable',
                'titre' => 'Le Lièvre et la Tortue : Version Béninoise',
                'texte' => '<h2>Le Défi</h2><p>Un jour, dans la savane béninoise, le lièvre arrogant défia la tortue à une course. "Tu es si lente, tu ne pourras jamais me battre !" se moqua-t-il.</p><h2>La Course</h2><p>La tortue accepta le défi avec sagesse. Pendant que le lièvre courait vite mais s\'arrêtait souvent pour se reposer, la tortue avançait lentement mais régulièrement.</p><h2>La Leçon</h2><p>À la fin, c\'est la tortue qui gagna. La morale de cette fable béninoise : "La persévérance et la régularité triomphent toujours de la vitesse et de l\'arrogance."</p>',
                'est_premium' => false,
                'prix' => null
            ],
            
            // PROVERBE / SAGESSE
            [
                'type' => 'Proverbe / Sagesse',
                'titre' => 'Proverbes Béninois : Sagesse Ancestrale',
                'texte' => '<h2>Collection de Proverbes</h2><ul><li><strong>"Quand les racines d\'un arbre commencent à pourrir, l\'arbre ne tarde pas à mourir."</strong> - Signifie que sans fondations solides, rien ne peut durer.</li><li><strong>"Un seul doigt ne peut pas laver le visage."</strong> - L\'union fait la force.</li><li><strong>"Le lézard qui veut devenir serpent doit d\'abord perdre ses pattes."</strong> - On ne peut pas tout avoir.</li><li><strong>"Quand l\'éléphant marche, l\'herbe souffre."</strong> - Les puissants ont un impact sur les faibles.</li></ul><h2>L\'Importance des Proverbes</h2><p>Ces proverbes transmettent la sagesse des ancêtres et guident encore aujourd\'hui les décisions importantes dans la société béninoise.</p>',
                'est_premium' => true,
                'prix' => 1500
            ],
            
            // CHANSON / MUSIQUE
            [
                'type' => 'Chanson / Musique',
                'titre' => 'Les Rythmes Traditionnels du Bénin',
                'texte' => '<h2>Introduction à la Musique Béninoise</h2><p>La musique béninoise est caractérisée par ses rythmes complexes et ses instruments traditionnels uniques.</p><h2>Instruments Traditionnels</h2><ul><li><strong>Le Tam-tam</strong> : Tambour principal utilisé dans les cérémonies</li><li><strong>Le Balafon</strong> : Instrument à percussion en bois</li><li><strong>Le Kora</strong> : Harpe-luth à 21 cordes</li><li><strong>Le Gangan</strong> : Tambour d\'aisselle</li></ul><h2>Styles Musicaux</h2><p>Le Bénin est connu pour ses styles musicaux variés : l\'Agbadja, le Tchinkoumé, et bien d\'autres qui expriment l\'âme du peuple béninois.</p>',
                'est_premium' => true,
                'prix' => 3000
            ],
            
            // DANSE TRADITIONNELLE
            [
                'type' => 'Danse traditionnelle',
                'titre' => 'La Danse Zangbéto : Gardiens de la Nuit',
                'texte' => '<h2>Origines de la Danse Zangbéto</h2><p>La danse Zangbéto est une danse traditionnelle sacrée pratiquée par la communauté Fon du Bénin. Elle est associée à la société secrète des Zangbéto, considérés comme les gardiens de la nuit.</p><h2>Les Mouvements</h2><p>Les danseurs portent des masques impressionnants et effectuent des mouvements rythmés accompagnés de tambours. Chaque mouvement a une signification symbolique liée à la protection et à la justice.</p><h2>Signification Culturelle</h2><p>Cette danse représente la force, le courage et la protection de la communauté. Elle est souvent exécutée lors de cérémonies importantes et de festivals culturels.</p>',
                'est_premium' => false,
                'prix' => null
            ],
            
            // RECETTE CULINAIRE
            [
                'type' => 'Recette culinaire',
                'titre' => 'Recette du Poulet DG : Plat Emblématique du Bénin',
                'texte' => '<h2>Ingrédients</h2><ul><li>1 poulet entier coupé en morceaux</li><li>3 plantains mûrs</li><li>2 oignons</li><li>3 tomates</li><li>2 piments</li><li>Gingembre, ail</li><li>Huile de palme</li><li>Épices (curry, poivre, sel)</li></ul><h2>Préparation</h2><ol><li>Mariner le poulet avec l\'ail, le gingembre et les épices pendant 30 minutes</li><li>Faire frire les plantains coupés en rondelles</li><li>Faire revenir le poulet dans l\'huile de palme</li><li>Ajouter les oignons, tomates et piments</li><li>Mélanger avec les plantains frits</li><li>Laisser mijoter 15 minutes</li></ol><h2>Service</h2><p>Servir chaud avec du riz ou de l\'attiéké. Bon appétit !</p>',
                'est_premium' => true,
                'prix' => 2000
            ],
            
            // ARTISANAT
            [
                'type' => 'Artisanat',
                'titre' => 'L\'Art du Bronze Béninois : Techniques Ancestrales',
                'texte' => '<h2>Histoire du Bronze au Bénin</h2><p>Le Bénin est célèbre pour son art du bronze, notamment les plaques et sculptures du royaume du Dahomey. Ces œuvres sont reconnues comme patrimoine mondial de l\'UNESCO.</p><h2>Technique de la Cire Perdue</h2><p>La technique traditionnelle utilisée est la "cire perdue" :<ol><li>Création d\'un modèle en cire</li><li>Recouvrement avec de l\'argile</li><li>Fusion de la cire</li><li>Coulée du bronze</li><li>Finition et polissage</li></ol></p><h2>Symbolisme</h2><p>Chaque sculpture raconte une histoire, représente un roi, une bataille ou un événement historique important du royaume.</p>',
                'est_premium' => true,
                'prix' => 4000
            ],
            
            // CÉRÉMONIE / RITUEL
            [
                'type' => 'Cérémonie / Rituel',
                'titre' => 'La Cérémonie du Vodoun : Rituel Sacré',
                'texte' => '<h2>Qu\'est-ce que le Vodoun ?</h2><p>Le Vodoun est une religion traditionnelle pratiquée au Bénin, reconnue comme religion officielle depuis 1996. C\'est un système de croyances complexe qui honore les esprits et les ancêtres.</p><h2>Les Cérémonies</h2><p>Les cérémonies vodoun sont des événements communautaires importants qui incluent :<ul><li>Des danses rituelles</li><li>Des offrandes aux divinités</li><li>Des consultations avec les prêtres</li><li>Des bénédictions pour la communauté</li></ul></p><h2>Le Jour National du Vodoun</h2><p>Chaque 10 janvier, le Bénin célèbre le Jour National du Vodoun, une journée de fête et de célébration de cette tradition ancestrale.</p>',
                'est_premium' => false,
                'prix' => null
            ],
            
            // PERSONNAGE HISTORIQUE
            [
                'type' => 'Personnage historique',
                'titre' => 'Le Roi Béhanzin : Dernier Roi du Dahomey',
                'texte' => '<h2>Biographie</h2><p>Béhanzin (1844-1906) fut le onzième et dernier roi du Dahomey. Il régna de 1889 à 1894 et est considéré comme l\'un des plus grands résistants à la colonisation française en Afrique.</p><h2>Le Règne</h2><p>Pendant son règne, Béhanzin organisa une résistance farouche contre les forces coloniales françaises. Il était connu pour son courage, sa stratégie militaire et son amour pour son peuple.</p><h2>La Résistance</h2><p>Malgré sa défaite finale et son exil en Martinique puis en Algérie, Béhanzin reste un symbole de résistance et de fierté nationale pour les Béninois.</p><h2>Héritage</h2><p>Aujourd\'hui, de nombreuses rues, places et monuments portent son nom au Bénin, perpétuant ainsi sa mémoire.</p>',
                'est_premium' => true,
                'prix' => 3500
            ],
            
            // LIEU CULTUREL
            [
                'type' => 'Lieu culturel',
                'titre' => 'Ouidah : La Porte du Non-Retour',
                'texte' => '<h2>Histoire d\'Ouidah</h2><p>Ouidah est une ville côtière du Bénin, tristement célèbre pour son rôle dans la traite des esclaves. La "Porte du Non-Retour" est un monument commémoratif érigé en 1995.</p><h2>Le Monument</h2><p>Ce monument symbolise le point de départ des millions d\'Africains déportés vers les Amériques. C\'est un lieu de mémoire et de recueillement pour les descendants d\'esclaves du monde entier.</p><h2>Le Musée</h2><p>Le musée d\'histoire d\'Ouidah retrace cette période sombre de l\'histoire et rend hommage aux victimes de la traite transatlantique.</p><h2>Visite</h2><p>Ouidah est aujourd\'hui un lieu de pèlerinage et de réflexion sur l\'histoire et l\'héritage de l\'esclavage.</p>',
                'est_premium' => false,
                'prix' => null
            ],
            
            // POÈME
            [
                'type' => 'Poème',
                'titre' => 'Hommage au Bénin : Poème en Français',
                'texte' => '<div style="font-style: italic; line-height: 2;"><p><strong>Ô Bénin, terre de mes ancêtres,</strong></p><p>Où les palmiers dansent au rythme du vent,</p><p>Où les tambours résonnent dans les cœurs,</p><p>Où la sagesse coule comme l\'eau des rivières.</p><br><p>Ton sol fertile a vu naître des rois,</p><p>Des guerriers, des sages, des artistes,</p><p>Qui ont forgé ton histoire glorieuse,</p><p>Et ont légué leur courage à leurs enfants.</p><br><p>De Porto-Novo à Cotonou,</p><p>De Ouidah à Abomey,</p><p>Chaque ville raconte une histoire,</p><p>Chaque pierre porte une mémoire.</p><br><p>Bénin, je chante ta beauté,</p><p>Ta culture, ta diversité,</p><p>Ton peuple fier et généreux,</p><p>Qui continue de briller dans le monde.</p></div>',
                'est_premium' => true,
                'prix' => 1000
            ],
            
            // VIDÉO
            [
                'type' => 'Vidéo',
                'titre' => 'Documentaire : Les Palais Royaux d\'Abomey',
                'texte' => '<h2>Les Palais Royaux d\'Abomey</h2><p>Ce documentaire explore les magnifiques palais royaux d\'Abomey, classés au patrimoine mondial de l\'UNESCO depuis 1985.</p><h2>Contenu du Documentaire</h2><ul><li>Histoire des 12 rois du Dahomey</li><li>Architecture unique des palais</li><li>Collections d\'objets royaux</li><li>Rituels et cérémonies traditionnelles</li><li>Entretiens avec des historiens et des gardiens de la tradition</li></ul><h2>Importance Culturelle</h2><p>Ces palais représentent un témoignage exceptionnel de la civilisation du royaume du Dahomey et de son influence sur l\'histoire de l\'Afrique de l\'Ouest.</p><p><em>Note : Ce contenu inclurait une vidéo documentaire complète une fois les médias uploadés.</em></p>',
                'est_premium' => true,
                'prix' => 5000
            ],
            
            // GALERIE PHOTO
            [
                'type' => 'Galerie photo',
                'titre' => 'Galerie : Les Masques Traditionnels du Bénin',
                'texte' => '<h2>Collection de Masques</h2><p>Cette galerie présente une collection exceptionnelle de masques traditionnels béninois, chacun avec sa propre signification et son usage rituel.</p><h2>Types de Masques</h2><ul><li><strong>Masques Gelede</strong> : Utilisés dans les cérémonies de la société Gelede pour honorer les mères</li><li><strong>Masques Zangbéto</strong> : Représentant les gardiens de la nuit</li><li><strong>Masques Egungun</strong> : Pour les cérémonies de commémoration des ancêtres</li><li><strong>Masques Guèlèdè</strong> : Masques de danse cérémonielle</li></ul><h2>Signification</h2><p>Chaque masque raconte une histoire, représente un esprit ou un ancêtre, et joue un rôle essentiel dans les rituels et cérémonies traditionnelles.</p><p><em>Note : Cette galerie inclurait des photos haute résolution une fois les médias uploadés.</em></p>',
                'est_premium' => false,
                'prix' => null
            ],
            
            // DOCUMENT
            [
                'type' => 'Document',
                'titre' => 'Archives : Traité de Paix de 1894',
                'texte' => '<h2>Document Historique</h2><p>Ce document historique retranscrit le traité de paix signé entre le roi Béhanzin et les autorités coloniales françaises en 1894, marquant la fin du royaume indépendant du Dahomey.</p><h2>Contenu du Traité</h2><p>Le traité contient les clauses suivantes :<ul><li>Reconnaissance de la souveraineté française</li><li>Conditions de reddition du roi</li><li>Protection des populations locales</li><li>Préservation de certaines traditions</li></ul></p><h2>Importance</h2><p>Ce document est essentiel pour comprendre la transition du Dahomey vers la colonisation et son impact sur la société béninoise moderne.</p><p><em>Note : Ce contenu inclurait le document PDF complet une fois les médias uploadés.</em></p>',
                'est_premium' => true,
                'prix' => 2500
            ],
        ];
        
        $created = 0;
        
        foreach ($contenus as $contenuData) {
            try {
                // Trouver le type de contenu
                $typeContenu = $typesContenus->firstWhere('nom_contenu', $contenuData['type']);
                
                if (!$typeContenu) {
                    $this->command->warn("Type de contenu '{$contenuData['type']}' non trouvé, création...");
                    $typeData = ['nom_contenu' => $contenuData['type']];
                    // Ajouter description seulement si la colonne existe
                    if (\Illuminate\Support\Facades\Schema::hasColumn('type_contenus', 'description')) {
                        $typeData['description'] = 'Type de contenu : ' . $contenuData['type'];
                    }
                    $typeContenu = TypeContenu::create($typeData);
                    $typesContenus->push($typeContenu); // Ajouter à la collection pour éviter de recréer
                }
                
                // Vérifier si le contenu existe déjà
                $exists = Contenu::where('titre', $contenuData['titre'])->exists();
                
                if (!$exists) {
                    // Déterminer les IDs corrects selon la structure de la table
                    $regionId = $region->id_region ?? $region->id ?? 1;
                    $langueId = $langue->id_langue ?? $langue->id ?? 1;
                    $typeContenuId = $typeContenu->id_type_contenu ?? $typeContenu->id ?? 1;
                    $auteurId = $auteur->id_utilisateur ?? $auteur->id ?? 1;
                    
                    Contenu::create([
                        'titre' => $contenuData['titre'],
                        'texte' => $contenuData['texte'],
                        'id_region' => $regionId,
                        'id_langue' => $langueId,
                        'id_type_contenu' => $typeContenuId,
                        'id_auteur' => $auteurId,
                        'statut' => 'valide',
                        'date_creation' => Carbon::now()->subDays(rand(1, 30)),
                        'date_validation' => Carbon::now()->subDays(rand(1, 30)),
                        'id_moderateur' => $auteurId,
                        'est_premium' => $contenuData['est_premium'],
                        'prix' => $contenuData['prix']
                    ]);
                    $created++;
                }
            } catch (\Exception $e) {
                $this->command->error("Erreur lors de la création du contenu '{$contenuData['titre']}': " . $e->getMessage());
                continue;
            }
        }
        
        $this->command->info("✅ {$created} contenus créés avec succès !");
        $this->command->info("📊 Répartition : " . Contenu::where('est_premium', true)->count() . " premium, " . Contenu::where('est_premium', false)->count() . " gratuits");
    }
}

