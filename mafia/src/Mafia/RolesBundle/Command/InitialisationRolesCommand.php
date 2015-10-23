<?php
namespace Mafia\RolesBundle\Command;

use Mafia\RolesBundle\Entity\Categorie;
use Mafia\RolesBundle\Entity\CategorieCompo;
use Mafia\RolesBundle\Entity\Composition;
use Mafia\RolesBundle\Entity\Crime;
use Mafia\RolesBundle\Entity\FactionEnum;
use Mafia\RolesBundle\Entity\Importance;
use Mafia\RolesBundle\Entity\Role;
use Mafia\RolesBundle\Entity\RolesCompos;
use Mafia\RolesBundle\Entity\RolesEnum;
use SplFixedArray;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class InitialisationRolesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('initialisation:role')
            ->setDescription('Remplir la bdd avec les rôles, crimes et catégories et composition officielle')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $output->writeln("Creation des crimes");

        $newCrimes = new SplFixedArray(10);
        $newCrimes[0] = new Crime();
        $newCrimes[0]->setNomCrime("Conspiration");
        $newCrimes[1] = new Crime();
        $newCrimes[1]->setNomCrime("Meurtre");
        $newCrimes[2] = new Crime();
        $newCrimes[2]->setNomCrime("Racolage");
        $newCrimes[3] = new Crime();
        $newCrimes[3]->setNomCrime("Intrusion");
        $newCrimes[4] = new Crime();
        $newCrimes[4]->setNomCrime("Trouble de l'ordre public");
        $newCrimes[5] = new Crime();
        $newCrimes[5]->setNomCrime("Kidnapping");
        $newCrimes[6] = new Crime();
        $newCrimes[6]->setNomCrime("Corruption");
        $newCrimes[7] = new Crime();
        $newCrimes[7]->setNomCrime("Destruction de propriété");
        $newCrimes[8] = new Crime();
        $newCrimes[8]->setNomCrime("Usurpation d'identité");
        $newCrimes[9] = new Crime();
        $newCrimes[9]->setNomCrime("Incendie criminel");
        foreach($newCrimes as $crime)
        {
            $em->persist($crime);
            $output->write(".");
        }

        $output->writeln("\nCreation des catégories");
        $newCategories = new SplFixedArray(19);
        $newCategories[0] = new Categorie();
        $newCategories[0]->setNomCategorie("Ville Investigateur");
        $newCategories[1] = new Categorie();
        $newCategories[1]->setNomCategorie("Ville Protection");
        $newCategories[2] = new Categorie();
        $newCategories[2]->setNomCategorie("Ville Gouvernement");
        $newCategories[3] = new Categorie();
        $newCategories[3]->setNomCategorie("Ville Tueur");
        $newCategories[4] = new Categorie();
        $newCategories[4]->setNomCategorie("Ville Pouvoir");
        $newCategories[5] = new Categorie();
        $newCategories[5]->setNomCategorie("Mafia Déception");
        $newCategories[6] = new Categorie();
        $newCategories[6]->setNomCategorie("Mafia Tueur");
        $newCategories[7] = new Categorie();
        $newCategories[7]->setNomCategorie("Mafia Support");
        $newCategories[8] = new Categorie();
        $newCategories[8]->setNomCategorie("Triade Déception");
        $newCategories[9] = new Categorie();
        $newCategories[9]->setNomCategorie("Triade Tueur");
        $newCategories[10] = new Categorie();
        $newCategories[10]->setNomCategorie("Triade Support");
        $newCategories[11] = new Categorie();
        $newCategories[11]->setNomCategorie("Neutre Bénin");
        $newCategories[12] = new Categorie();
        $newCategories[12]->setNomCategorie("Neutre Malveillant");
        $newCategories[13] = new Categorie();
        $newCategories[13]->setNomCategorie("Neutre Tueur");
        $newCategories[14] = new Categorie();
        $newCategories[14]->setNomCategorie("Ville Aleatoire");
        $newCategories[15] = new Categorie();
        $newCategories[15]->setNomCategorie("Mafia Aleatoire");
        $newCategories[16] = new Categorie();
        $newCategories[16]->setNomCategorie("Triade Aleatoire");
        $newCategories[17] = new Categorie();
        $newCategories[17]->setNomCategorie("Neutre Aleatoire");
        $newCategories[18] = new Categorie();
        $newCategories[18]->setNomCategorie("Role Aleatoire");
        foreach($newCategories as $categorie)
        {
            $em->persist($categorie);
            $output->write(".");
        }

        $output->writeln("\nCreation des rôles");
        $newImportanceBase = new SplFixedArray(54);
        $newRoles = new SplFixedArray(54);
        for($i = 1; $i < 54; $i++)
        {
            $newRoles[$i] = new Role();
            $newRoles[$i]->addCategorieRole($newCategories[18]);
            $newRoles[$i]->setEnumRole($i);
            if($i < 20)
            {
                $newRoles[$i]->setEnumFaction(FactionEnum::VILLE);
                $newRoles[$i]->addCategorieRole($newCategories[14]);
            }
            elseif($i < 31)
            {
                $newRoles[$i]->setEnumFaction(FactionEnum::MAFIA);
                $newRoles[$i]->addCategorieRole($newCategories[15]);
            }
            elseif($i < 43)
            {
                $newRoles[$i]->setEnumFaction(FactionEnum::NEUTRE);
                $newRoles[$i]->addCategorieRole($newCategories[17]);
            }
            else
            {
                $newRoles[$i]->setEnumFaction(FactionEnum::TRIADE);
                $newRoles[$i]->addCategorieRole($newCategories[16]);
            }

            switch($i)
            {
                case RolesEnum::CRIEUR:
                    $newRoles[$i]->setNomRole("Crieur");
                    $newRoles[$i]->setDescription("Le Crieur aime se faire entendre, surtout la nuit");
                    $newRoles[$i]->setDescriptionPrincipale("Peut communiquer avec toute la ville la nuit");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[4]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::DRAGON:
                    $newRoles[$i]->setNomRole("Dragon");
                    $newRoles[$i]->setDescription("Le Dragon est tout feu tout flamme à l'idée de réduire la Mafia en cendre !");
                    $newRoles[$i]->setDescriptionPrincipale("Dirige la Triade, il peut communiquer avec elle la nuit et faire assassiner quelqu'un");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[9]);
                    break;
                case RolesEnum::PARRAIN:
                    $newRoles[$i]->setNomRole("Parrain");
                    $newRoles[$i]->setDescription("Le Parrain va vous faire une offre que vous ne pourrez refuser.");
                    $newRoles[$i]->setDescriptionPrincipale("Dirige la Mafia, il peut communiquer avec elle la nuit et faire assassiner quelqu'un");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[6]);
                    break;
                case RolesEnum::JUGE:
                    $newRoles[$i]->setNomRole("Juge");
                    $newRoles[$i]->setDescription("Non mais allô quoi, dit le Juge avant de faire appel.");
                    $newRoles[$i]->setDescriptionPrincipale("Le juge a le pouvoir de lancer une court d'appel. Cela coupe tout les discussion et force le vote à etre anonyme.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    break;
                case RolesEnum::MARSHALL:
                    $newRoles[$i]->setNomRole("Marshall");
                    $newRoles[$i]->setDescription("Le Marshall aime voir plusieurs corps pendus devant le soleil couchant.");
                    $newRoles[$i]->setDescriptionPrincipale("Le Marshall peut autoriser le lynchage de plusieurs personne le même jour.");
                    $newRoles[$i]->setCapacite("Lynchage de masse");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[6]);
                    $newRoles[$i]->addCategorieRole($newCategories[2]);
                    break;
                case RolesEnum::MACON_CHEF:
                    $newRoles[$i]->setNomRole("Chef des Franc-Maçons");
                    $newRoles[$i]->setDescription("Le chef des Franc-Maçon est en fait menuisier et faux-cul, comme quoi, tout se perd...");
                    $newRoles[$i]->setDescriptionPrincipale("Le Chef des Maçons essaie de recruter des membre de la Ville pour les faire devenir des franc-maçons. Cette capacité fonctionne uniquement sur les Citoyens.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[0]);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[2]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[2]);
                    break;
                case RolesEnum::MAIRE:
                    $newRoles[$i]->setNomRole("Maire");
                    $newRoles[$i]->setDescription("Le Maire de la Ville est un planqué ! Bon faut avouer que tout le monde veut le tuer aussi...");
                    $newRoles[$i]->setDescriptionPrincipale("Si le Maire révèle son rôle (avec la commande -vote), sa voix comptera plus que celle des autres personnes de la ville.");
                    $newRoles[$i]->setCapacite("Vote amélioré");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[6]);
                    $newRoles[$i]->addCategorieRole($newCategories[2]);
                    break;
                case RolesEnum::GOUROU:
                    $newRoles[$i]->setNomRole("Gourou");
                    $newRoles[$i]->setDescription("Le Gourou d'un culte travaillant dans l'ombre... D'aucuns le surnomment Cruise, allez savoir pourquoi");
                    $newRoles[$i]->setDescriptionPrincipale("Membre du culte avec des pouvoirs de guérisseur. Chaque personne sauvée par le Gourou se convertira au Culte.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[0]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    break;
                case RolesEnum::CITOYEN:
                    $newRoles[$i]->setNomRole("Citoyen");
                    $newRoles[$i]->setDescription('Un citoyen dont les seules forces sont une protection à usage unique ... et un gilet pare-balle');
                    $newRoles[$i]->setDescriptionPrincipale("Un simple citoyen avec un gilet pare-balle.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[2]);
                    break;
                case RolesEnum::CONDUCTEUR_DE_BUS:
                    $newRoles[$i]->setNomRole("Conducteur de bus");
                    $newRoles[$i]->setDescription("Embarquez pour le bus magique !");
                    $newRoles[$i]->setDescriptionPrincipale("Il a la compétence d'échanger la position de deux joueur chaque nuit. Les actions nocturnes qui visaient un des joueur échangé sont redirigées vers l'autre. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::DETECTIVE:
                    $newRoles[$i]->setNomRole("Détective");
                    $newRoles[$i]->setDescription("Pour lui, la victoire est élémentaire très cher");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut suivre la trace d'un joueur chaque nuit. Il découvre ainsi quelles ont été les personnes visées par la cible.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::DOCTEUR:
                    $newRoles[$i]->setNomRole("Docteur");
                    $newRoles[$i]->setDescription("Qui? Le docteur ! Où? Dans sa maison !");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut sauver un joueur chaque nuit.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    break;
                case RolesEnum::ESCORT:
                    $newRoles[$i]->setNomRole("Escort-Girl");
                    $newRoles[$i]->setDescription("C'est en couchant avec n'importe quoi qu'on devient n'importe qui - Une escort assez gaillarde");
                    $newRoles[$i]->setDescriptionPrincipale("Ce rôle permet de bloquer le rôle d'un joueur chaque nuit, l'empéchant d'utiliser leur capacité nocturne.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[2]);
                    $newRoles[$i]->addCrimeRole($newCrimes[4]);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    break;
                case RolesEnum::ESPION:
                    $newRoles[$i]->setNomRole("Espion");
                    $newRoles[$i]->setDescription("A force de faire autant de Bond, il a fini par avoir mal au Q");
                    $newRoles[$i]->setDescriptionPrincipale(" Il peut voir le chat de la mafia la nuit (les noms sont masqués) ainsi que celui des franc-maçons.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::MACON:
                    $newRoles[$i]->setNomRole("Franc-Maçon");
                    $newRoles[$i]->setDescription("A ne pas confondre avec l'Eurostar");
                    $newRoles[$i]->setDescriptionPrincipale("Il connait l'identité des autres Franc-maçons ainsi que du Chef des maçons, et il peut communiquer avec eux la nuit.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[2]);
                    break;
                case RolesEnum::GARDE_DU_CORPS:
                    $newRoles[$i]->setNomRole("Garde du corps");
                    $newRoles[$i]->setDescription("Move your body...Guard !");
                    $newRoles[$i]->setDescriptionPrincipale("Si le joueur qu'il a ciblé est attaqué pendant la nuit, le garde du cops meurt et l'attaquant meurent, et le joueur défendu n'est pas tué.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[3]);
                    break;
                case RolesEnum::GARDIEN_DE_PRISON:
                    $newRoles[$i]->setNomRole("Gardien de prison");
                    $newRoles[$i]->setDescription("Gaffe à la savonette");
                    $newRoles[$i]->setDescriptionPrincipale("Le Gardien de prison peut emprisonner et bloquer le rôle d'une personne chaque nuit qui suit un jour sans lynchage. Il peut parler anonymement à la personne emprisonnée, et optionnellement les tuer. Il doit utiliser la commande -jail le jour pour choisir sa cible.");
                    $newRoles[$i]->setCapacite("Commande -jail");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[5]);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::INSPECTEUR:
                    $newRoles[$i]->setNomRole("Inspecteur");
                    $newRoles[$i]->setDescription("Avec ou sans gadget, cet inspecteur se débrouille bien");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut enquêter sur un joueur par nuit, révélant un crime qu'il a commis.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::JUSTICIER:
                    $newRoles[$i]->setNomRole("Justicier");
                    $newRoles[$i]->setDescription("Tel un animal nocturne, il surgit de la nuit pour fondre sur le mal de la ville !");
                    $newRoles[$i]->setDescriptionPrincipale("Peut assassiner quelqu'un la nuit.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[3]);
                    break;
                case RolesEnum::MEDECIN_LEGISTE:
                    $newRoles[$i]->setNomRole("Médecin légiste");
                    $newRoles[$i]->setDescription("Un médecin maniant le scalpel à la perfection");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut révéler le rôle des morts si ils sont cachés.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::SHERIFF:
                    $newRoles[$i]->setNomRole("Sheriff");
                    $newRoles[$i]->setDescription("Il fait régner la loi dans ce monde de fous");
                    $newRoles[$i]->setDescriptionPrincipale("Il a le pouvoir de vérifier l'innocence d'un joueur la nuit.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::VEILLEUR:
                    $newRoles[$i]->setNomRole("Veilleur");
                    $newRoles[$i]->setDescription("Cet homme qui refuse de la mettre en veilleuse n'est pourtant pas une lumière");
                    $newRoles[$i]->setDescriptionPrincipale("Le Veilleur peut observer un joueur et vois qui lui a rendu visite.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::VETERAN:
                    $newRoles[$i]->setNomRole("Veteran");
                    $newRoles[$i]->setDescription("Il est un peu du genre à tirer avant et poser des questions après... précoce.");
                    $newRoles[$i]->setDescriptionPrincipale("Il tue automatiquement tous les joueurs qui le visent quand il est en alerte.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[7]);
                    $newRoles[$i]->addCategorieRole($newCategories[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::AGENT:
                    $newRoles[$i]->setNomRole("Agent");
                    $newRoles[$i]->setDescription("Collecte des informations pour la Mafia");
                    $newRoles[$i]->setDescriptionPrincipale("Il piste une personne la nuit, il voit à qui la personne a rendu visite et qui lui a rendu visite chaque nuit. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::PILLEUR:
                    $newRoles[$i]->setNomRole("Pilleur");
                    $newRoles[$i]->setDescription("Un pilleur n'est jamais en retard, jamais en avance non plus d'ailleurs. Il est toujours pile à l'heure.");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut se cacher dans la maison d'un autre joueur, cela permet de rediriger les actions affectant le Pilleur vers sa cible.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[5]);
                    break;
                case RolesEnum::MAITRE_CHANTEUR:
                    $newRoles[$i]->setNomRole("Maître chanteur");
                    $newRoles[$i]->setDescription("Le maître aime beaucoup s'occuper de sa chorale");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut empêcher une personne de parler une fois pas jour.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::CONSEILLER:
                    $newRoles[$i]->setNomRole("Conseiller");
                    $newRoles[$i]->setDescription("Pas toujours de bon conseil pour autant");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut enquêter sur un joueur chaque nuit afin de déterminer les crimes qu'il a commis. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::PROSTITUEE:
                    $newRoles[$i]->setNomRole("Prostituée");
                    $newRoles[$i]->setDescription("La moins chère sur le marché !");
                    $newRoles[$i]->setDescriptionPrincipale("Elle peut bloquer un joueur chaque nuit, l’empêchant d'utiliser sa capacité nocturne.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[2]);
                    $newRoles[$i]->addCrimeRole($newCrimes[4]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::MAITRE_DU_DEGUISEMENT:
                    $newRoles[$i]->setNomRole("Maître du déguisement");
                    $newRoles[$i]->setDescription("Petit, il aimait déjà beaucoup se travestir !");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut tuer un joueur et subtiliser son identité. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[8]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    $newRoles[$i]->addCategorieRole($newCategories[5]);
                    break;
                case RolesEnum::CONTREFACTEUR:
                    $newRoles[$i]->setNomRole("Contrefacteur");
                    $newRoles[$i]->setDescription("Malgré son nom, celui-ci n'a rien contre le fait de recevoir du courrier");
                    $newRoles[$i]->setDescriptionPrincipale("Un Contrefacteur peut piéger l'habitation d'un joueur chaque nuit. Si un Sheriff enquête sur sa cible, il apparaitra en tant que 'Membre de la Mafia' ou en tant qu'un des rôle Neutre Malveillant encore en vie.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[5]);
                    break;
                case RolesEnum::CONCIERGE:
                    $newRoles[$i]->setNomRole("Concierge");
                    $newRoles[$i]->setDescription("Personne n'aime ce pauvre Cierge...");
                    $newRoles[$i]->setDescriptionPrincipale("Le Concierge peut masquer le rôle d'un joueur mort chaque nuit, si sa cible meurt, le rôle (et les dernières volontés) ne sont pas révélées le matin suivant.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCrimeRole($newCrimes[7]);
                    $newRoles[$i]->addCategorieRole($newCategories[5]);
                    break;
                case RolesEnum::KIDNAPPER:
                    $newRoles[$i]->setNomRole("Kidnapper");
                    $newRoles[$i]->setDescription("Un gardien de prison mafioso, mais sans prison");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut emprisonner un joueur et bloquer son rôle chaque nuit qui suit un jour sans lynchage. Il doit utiliser la commande -jail le jour pour choisir sa cible.");
                    $newRoles[$i]->setCapacite("Commande -jail");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[5]);
                    $newRoles[$i]->addCategorieRole($newCategories[6]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::MAFIOSO:
                    $newRoles[$i]->setNomRole("Mafioso");
                    $newRoles[$i]->setDescription("Un simple mafieu");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut suggérer de tuer quelqu'un la nuit, la personne qui reçoit le plus de votes sera tuée par un des Mafioso.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[6]);
                    break;
                case RolesEnum::ADMINISTRATEUR:
                    $newRoles[$i]->setNomRole("Administrateur");
                    $newRoles[$i]->setDescription("Le conseiller de monsieur le Dragon");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut enquêter sur un joueur chaque nuit afin de déterminer les crimes qu'il a commis.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::IMPOSTEUR:
                    $newRoles[$i]->setNomRole("Imposteur");
                    $newRoles[$i]->setDescription("Pas de faux-semblant avec lui");
                    $newRoles[$i]->setDescriptionPrincipale("Le Pilleur peut se cacher dans la maison d'un autre joueur, cela permet de rediriger les actions affectant le Pilleur vers sa cible.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[8]);
                    break;
                case RolesEnum::TUEUR_A_GAGE:
                    $newRoles[$i]->setNomRole("Tueur à gage");
                    $newRoles[$i]->setDescription("Un simple membre de la Triade");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut suggérer de tuer quelqu'un la nuit, la personne qui reçoit le plus de votes sera tuée par un des Mafioso.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[9]);
                    break;
                case RolesEnum::FAUSSAIRE:
                    $newRoles[$i]->setNomRole("Faussaire");
                    $newRoles[$i]->setDescription("Quand on le jette d'un avion, on a un faussaire volant");
                    $newRoles[$i]->setDescriptionPrincipale("Un Faussaire peut piéger l'habitation d'un joueur chaque nuit. Si un Sheriff enquête sur sa cible, il apparaitra en tant que 'Membre de la Mafia' ou en tant qu'un des rôle Neutre Malveillant encore en vie.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[8]);
                    break;
                case RolesEnum::MAITRE_ENCENS:
                    $newRoles[$i]->setNomRole("Maitre de l'encens");
                    $newRoles[$i]->setDescription("Dans la triade, le concierge ne nettoie pas, il parfume");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut masquer le rôle d'un joueur mort chaque nuit, si sa cible meurt, le rôle (et les dernières volontés) ne sont pas révélées le matin suivant. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCrimeRole($newCrimes[7]);
                    $newRoles[$i]->addCategorieRole($newCategories[8]);
                    break;
                case RolesEnum::INFORMATEUR:
                    $newRoles[$i]->setNomRole("Informateur");
                    $newRoles[$i]->setDescription("Le nom Maître du déguisement étant copyrighté par la mafia, il a fallu s'adapter");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut tuer un joueur et subtiliser son identité.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[8]);
                    $newRoles[$i]->addCategorieRole($newCategories[8]);
                    $newRoles[$i]->addCategorieRole($newCategories[9]);
                    break;
                case RolesEnum::INTERROGATEUR:
                    $newRoles[$i]->setNomRole("Interrogateur");
                    $newRoles[$i]->setDescription("Contrôle surprise !");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut emprisonner et bloquer le rôle d'une personne chaque nuit qui suit un jour sans lynchage. Il peut parler anonymement à la personne emprisonnée, et optionnellement les tuer. Il doit utiliser la commande -jail le jour pour choisir sa cible.");
                    $newRoles[$i]->setCapacite("Commande -jail");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[5]);
                    $newRoles[$i]->addCategorieRole($newCategories[9]);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::LIAISON:
                    $newRoles[$i]->setNomRole("Liaison");
                    $newRoles[$i]->setDescription("Cette femme est plutôt nerveuse, mais reste très dangereuse");
                    $newRoles[$i]->setDescriptionPrincipale("Ce rôle permet de bloquer le rôle d'un joueur chaque nuit, l'empéchant d'utiliser leur capacité nocturne.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[2]);
                    $newRoles[$i]->addCrimeRole($newCrimes[4]);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::MAITRE_SILENCE:
                    $newRoles[$i]->setNomRole("Maître du silence");
                    $newRoles[$i]->setDescription("Cette femme est plutôt nerveuse, mais reste très dangereuse");
                    $newRoles[$i]->setDescriptionPrincipale("Il peut empêcher une personne de parler une fois pas jour.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setDescription("1, 2 , 3 roi du silence !");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::AVANT_GARDE:
                    $newRoles[$i]->setNomRole("Avant-garde");
                    $newRoles[$i]->setDescription("Souvent mieux placé que l'arrière-garde...");
                    $newRoles[$i]->setDescriptionPrincipale("Il piste une personne la nuit, il voit à qui la personne a rendu visite et qui lui a rendu visite chaque nuit. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::AMNESIQUE:
                    $newRoles[$i]->setNomRole("Amnésique");
                    $newRoles[$i]->setDescription("Le pire, c'est que personne n'est fichu de retrouver ce certain Mnésique...");
                    $newRoles[$i]->setDescriptionPrincipale("L'Amnésique est un rôle neutre qui a la capacité de prendre le rôle d'un joueur décédé.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[11]);
                    break;
                case RolesEnum::PYROMANE:
                    $newRoles[$i]->setNomRole("Pyromane");
                    $newRoles[$i]->setDescription("C'est un oiseau ! C'est un avion ! NON C'est Pyromane !");
                    $newRoles[$i]->setDescriptionPrincipale("Le rôle a la capacité d'arroser une maison avec de l'essence. Lors d'une des nuit suivantes, il peut brûler toutes les maisons qui ont été arrosées, tuant leur habitant.");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCrimeRole($newCrimes[7]);
                    $newRoles[$i]->addCrimeRole($newCrimes[9]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    $newRoles[$i]->addCategorieRole($newCategories[13]);
                    break;
                case RolesEnum::AUDITEUR:
                    $newRoles[$i]->setNomRole("Auditeur");
                    $newRoles[$i]->setDescription("...");
                    $newRoles[$i]->setDescriptionPrincipale("L'Auditeur peut convertir les membres de la Ville en Citoyen. Les rôles neutres qui ne sont pas invulnérable la nuit sont convertis en Survivant. Un membre de la Mafia est transformé en Mafioso et un membre de la Triade en Tueur à gages. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[6]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    break;
                case RolesEnum::CULTISTE:
                    $newRoles[$i]->setNomRole("Cultiste");
                    $newRoles[$i]->setDescription("Les gens du culte sont cultistes, ceux du aute sont autre chose...");
                    $newRoles[$i]->setDescriptionPrincipale("Le Cultiste est un membre du Culte de l'ombre. Il a le pouvoir de convertir quelqu'un au Culte et de discuter avec les autres membres de ce même Culte durant la nuit. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[0]);
                    $newRoles[$i]->addCrimeRole($newCrimes[2]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    break;
                case RolesEnum::BOURREAU:
                    $newRoles[$i]->setNomRole("Bourreau");
                    $newRoles[$i]->setDescription("Des fois il le fait bas, pour changer...");
                    $newRoles[$i]->setDescriptionPrincipale("Le bourreau est un role neutre avec aucun pouvoir actif. Il gagne si sa cible est lynchée durant la journée. Si sa cible meurt la nuit, le bourreau pourra devenir un bouffon selon les options. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[11]);
                    break;
                case RolesEnum::BOUFFON:
                    $newRoles[$i]->setNomRole("Bouffon");
                    $newRoles[$i]->setDescription("De bourreau à bouffon, il n'y a qu'un pas");
                    $newRoles[$i]->setDescriptionPrincipale("Le Bouffon est un rôle neutre capable d'ennuyer les autres joueurs la nuit.Il gagne seulement si il se fait lyncher durant la journée.  ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[11]);
                    break;
                case RolesEnum::TUEUR_DE_MASSE:
                    $newRoles[$i]->setNomRole("Tueur de masse");
                    $newRoles[$i]->setDescription("Quand un fou s'équipe d'une bombe, ça laisse des traces");
                    $newRoles[$i]->setDescriptionPrincipale("Chaque nuit, le tueur de masse cible quelqu'un et tue toute personne dans cette maison (être chez lui signifiant le cibler avec son pouvoir). ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCrimeRole($newCrimes[7]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    $newRoles[$i]->addCategorieRole($newCategories[13]);
                    break;
                case RolesEnum::TUEUR_EN_SERIE:
                    $newRoles[$i]->setNomRole("Tueur en série");
                    $newRoles[$i]->setDescription("Coupe coupe !");
                    $newRoles[$i]->setDescriptionPrincipale("Le tueur en série a la capacité de tuer un joueur chaque nuit.Il doit éliminer la ville et toute autre faction hostile pour gagner.  ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    $newRoles[$i]->addCategorieRole($newCategories[13]);
                    break;
                case RolesEnum::SURVIVANT:
                    $newRoles[$i]->setNomRole("Survivant");
                    $newRoles[$i]->setDescription("WILSOOOOOOOOON");
                    $newRoles[$i]->setDescriptionPrincipale("Le survivant a un gilet pare-balle lui permettant de survivre aux attaques nocturnes.Il doit être vivant à la fin de la partie pour gagner, qu'importe qui gagne avec lui. ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[11]);
                    break;
                case RolesEnum::MARIONETTISTE:
                    $newRoles[$i]->setNomRole("Marionettiste");
                    $newRoles[$i]->setDescription("Pinocchion n'a qu'à bien se tenir !");
                    $newRoles[$i]->setDescriptionPrincipale("Ce rôle a le pouvoir de contrôler une cible la nuit, sélectionnant sur qui elle utilisera son pouvoir nocturne. Il gagne si la ville perd et qu'il est encore en vie.  ");
                    $newRoles[$i]->setCapacite("Aucune");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    break;
            }
            $newImportanceBase[$i] = new Importance();
            $newImportanceBase[$i]->setRole($newRoles[$i]);
            $newImportanceBase[$i]->setValeur(100);
            $em->persist($newRoles[$i]);
            $em->persist($newImportanceBase[$i]);
            $output->write(".");

        }

        $output->writeln("\nCreation de la composition officielle 15 joueurs");
        $newCompo = new Composition();
        $newCompo->setNomCompo("Officielle");
        $newCompo->setOfficielle(true);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::SHERIFF],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::SHERIFF]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::INSPECTEUR],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::INSPECTEUR]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::PARRAIN],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::PARRAIN]);

        $newCompo->addCategorieCompo(new CategorieCompo($newCategories[3],2));
        $newCompo->addCategorieCompo(new CategorieCompo($newCategories[2],1));
        $newCompo->addCategorieCompo(new CategorieCompo($newCategories[1],1));
        $newCompo->addCategorieCompo(new CategorieCompo($newCategories[4],1));
        $newCompo->addCategorieCompo(new CategorieCompo($newCategories[14],2));

        $newCompo->addCategorieCompo(new CategorieCompo($newCategories[11],2));
        $newCompo->addCategorieCompo(new CategorieCompo($newCategories[12],1));

        $newCompo->addCategorieCompo(new CategorieCompo($newCategories[15],2));

        $em->persist($newCompo);

        $output->writeln("\nCreation de la composition de test");
        $newCompo = new Composition();
        $newCompo->setNomCompo("TEST");
        $newCompo->setOfficielle(true);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::SHERIFF],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::SHERIFF]);
        //$newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::INSPECTEUR],1));
        //$newCompo->addImportance($newImportanceBase[RolesEnum::INSPECTEUR]);
        $newCompo->addCategorieCompo(new CategorieCompo($newCategories[6],1));

        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::TUEUR_EN_SERIE],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::TUEUR_EN_SERIE]);

        $em->persist($newCompo);
        $em->flush();
    }




} 