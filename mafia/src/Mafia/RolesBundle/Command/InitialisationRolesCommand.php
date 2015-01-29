<?php
namespace Mafia\RolesBundle\Command;

use Mafia\RolesBundle\Entity\Categorie;
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

        $output->writeln("Creation des catégories");
        $newCategories = new SplFixedArray(14);
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
        foreach($newCategories as $categorie)
        {
            $em->persist($categorie);
            $output->write(".");
        }

        $output->writeln("Creation des rôles");
        $newImportanceBase = new SplFixedArray(54);
        $newRoles = new SplFixedArray(54);
        for($i = 1; $i < 54; $i++)
        {
            $newRoles[$i] = new Role();
            $newRoles[$i]->setEnumRole($i);
            if($i < 20)
            {
                $newRoles[$i]->setEnumFaction(FactionEnum::VILLE);
            }
            elseif($i < 31)
            {
                $newRoles[$i]->setEnumFaction(FactionEnum::MAFIA);
            }
            elseif($i < 43)
            {
                $newRoles[$i]->setEnumFaction(FactionEnum::NEUTRE);
            }
            else
            {
                $newRoles[$i]->setEnumFaction(FactionEnum::TRIADE);
            }

            switch($i)
            {
                case RolesEnum::CRIEUR:
                    $newRoles[$i]->setNomRole("Crieur");
                    $newRoles[$i]->setDescription("Le Crieur aime se faire entendre, surtout la nuit");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[4]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::DRAGON:
                    $newRoles[$i]->setNomRole("Dragon");
                    $newRoles[$i]->setDescription("Le Dragon est tout feu tout flamme à l'idée de réduire la Mafia en cendre !");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[9]);
                    break;
                case RolesEnum::PARRAIN:
                    $newRoles[$i]->setNomRole("Parrain");
                    $newRoles[$i]->setDescription("Le Parrain va vous faire une offre que vous ne pourrez refuser.");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[6]);
                    break;
                case RolesEnum::JUGE:
                    $newRoles[$i]->setNomRole("Juge");
                    $newRoles[$i]->setDescription("Non mais allô quoi, dit le Juge avant de faire appel.");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    break;
                case RolesEnum::MARSHALL:
                    $newRoles[$i]->setNomRole("Marshall");
                    $newRoles[$i]->setDescription("Le Marshall aime voir plusieurs corps pendus devant le soleil couchant.");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[6]);
                    $newRoles[$i]->addCategorieRole($newCategories[2]);
                    break;
                case RolesEnum::MACON_CHEF:
                    $newRoles[$i]->setNomRole("Chef des Franc-Maçons");
                    $newRoles[$i]->setDescription("Le chef des Franc-Maçon est en fait menuisier et faux-cul, comme quoi, tout se perd...");
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
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[6]);
                    $newRoles[$i]->addCategorieRole($newCategories[2]);
                    break;
                case RolesEnum::SORCIER:
                    $newRoles[$i]->setNomRole("Gourou");
                    $newRoles[$i]->setDescription("Le Gourou d'un culte travaillant dans l'ombre... D'aucuns le surnomment Cruise, allez savoir pourquoi");
                    $newRoles[$i]->setRoleUnique(true);
                    $newRoles[$i]->addCrimeRole($newCrimes[0]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    break;
                case RolesEnum::CITOYEN:
                    $newRoles[$i]->setNomRole("Citoyen");
                    $newRoles[$i]->setDescription('Un citoyen dont les seules forces sont une protection à usage unique ... et un gilet pare-balle');
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[2]);
                    break;
                case RolesEnum::CONDUCTEUR_DE_BUS:
                    $newRoles[$i]->setNomRole("Conducteur de bus");
                    $newRoles[$i]->setDescription("Embarquez pour le bus magique !");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::DETECTIVE:
                    $newRoles[$i]->setNomRole("Détective");
                    $newRoles[$i]->setDescription("Pour lui, la victoire est élémentaire très cher");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::DOCTEUR:
                    $newRoles[$i]->setNomRole("Docteur");
                    $newRoles[$i]->setDescription("Qui? Le docteur ! Où? Dans sa maison !");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    break;
                case RolesEnum::ESCORT:
                    $newRoles[$i]->setNomRole("Escort-Girl");
                    $newRoles[$i]->setDescription("C'est en couchant avec n'importe quoi qu'on devient n'importe qui - Une escort assez gaillarde");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[2]);
                    $newRoles[$i]->addCrimeRole($newCrimes[4]);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    break;
                case RolesEnum::ESPION:
                    $newRoles[$i]->setNomRole("Espion");
                    $newRoles[$i]->setDescription("A force de faire autant de Bond, il a fini par avoir mal au Q");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::MACON:
                    $newRoles[$i]->setNomRole("Franc-Maçon");
                    $newRoles[$i]->setDescription("A ne pas confondre avec l'Eurostar");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[2]);
                    break;
                case RolesEnum::GARDE_DU_CORPS:
                    $newRoles[$i]->setNomRole("Garde du corps");
                    $newRoles[$i]->setDescription("Move your body...Guard !");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[3]);
                    break;
                case RolesEnum::GARDIEN_DE_PRISON:
                    $newRoles[$i]->setNomRole("Gardien de prison");
                    $newRoles[$i]->setDescription("Gaffe à la savonette");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[5]);
                    $newRoles[$i]->addCategorieRole($newCategories[1]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::INSPECTEUR:
                    $newRoles[$i]->setNomRole("Inspecteur");
                    $newRoles[$i]->setDescription("Avec ou sans gadget, cet inspecteur se débrouille bien");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::JUSTICIER:
                    $newRoles[$i]->setNomRole("Justicier");
                    $newRoles[$i]->setDescription("Tel un animal nocturne, il surgit de la nuit pour fondre sur le mal de la ville !");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[3]);
                    break;
                case RolesEnum::MEDECIN_LEGISTE:
                    $newRoles[$i]->setNomRole("Médecin légiste");
                    $newRoles[$i]->setDescription("Un médecin maniant le scalpel à la perfection");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::SHERIFF:
                    $newRoles[$i]->setNomRole("Sheriff");
                    $newRoles[$i]->setDescription("Il fait régner la loi dans ce monde de fous");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::VEILLEUR:
                    $newRoles[$i]->setNomRole("Veilleur");
                    $newRoles[$i]->setDescription("Cet homme qui refuse de la mettre en veilleuse n'est pourtant pas une lumière");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[0]);
                    break;
                case RolesEnum::VETERAN:
                    $newRoles[$i]->setNomRole("Veteran");
                    $newRoles[$i]->setDescription("Il est un peu du genre à tirer avant et poser des questions après... précoce.");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[7]);
                    $newRoles[$i]->addCategorieRole($newCategories[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[4]);
                    break;
                case RolesEnum::AGENT:
                    $newRoles[$i]->setNomRole("Agent");
                    $newRoles[$i]->setDescription("Collecte des informations pour la Mafia");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::PILLEUR:
                    $newRoles[$i]->setNomRole("Pilleur");
                    $newRoles[$i]->setDescription("Un pilleur n'est jamais en retard, jamais en avance non plus d'ailleurs. Il est toujours pile à l'heure.");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[5]);
                    break;
                case RolesEnum::MAITRE_CHANTEUR:
                    $newRoles[$i]->setNomRole("Maître chanteur");
                    $newRoles[$i]->setDescription("Le maître aime beaucoup s'occuper de sa chorale");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::CONSEILLER:
                    $newRoles[$i]->setNomRole("Conseiller");
                    $newRoles[$i]->setDescription("Pas toujours de bon conseil pour autant");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::PROSTITUEE:
                    $newRoles[$i]->setNomRole("Prostituée");
                    $newRoles[$i]->setDescription("La moins chère sur le marché !");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[2]);
                    $newRoles[$i]->addCrimeRole($newCrimes[4]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::MAITRE_DU_DEGUISEMENT:
                    $newRoles[$i]->setNomRole("Maître du déguisement");
                    $newRoles[$i]->setDescription("Petit, il aimait déjà beaucoup se travestir !");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[8]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    $newRoles[$i]->addCategorieRole($newCategories[5]);
                    break;
                case RolesEnum::CONTREFACTEUR:
                    $newRoles[$i]->setNomRole("Contrefacteur");
                    $newRoles[$i]->setDescription("Malgré son nom, celui-ci n'a rien contre le fait de recevoir du courrier");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[5]);
                    break;
                case RolesEnum::CONCIERGE:
                    $newRoles[$i]->setNomRole("Concierge");
                    $newRoles[$i]->setDescription("Personne n'aime ce pauvre Cierge...");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCrimeRole($newCrimes[7]);
                    $newRoles[$i]->addCategorieRole($newCategories[5]);
                    break;
                case RolesEnum::KIDNAPPER:
                    $newRoles[$i]->setNomRole("Kidnapper");
                    $newRoles[$i]->setDescription("Un gardien de prison mafioso, mais sans prison");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[5]);
                    $newRoles[$i]->addCategorieRole($newCategories[6]);
                    $newRoles[$i]->addCategorieRole($newCategories[7]);
                    break;
                case RolesEnum::MAFIOSO:
                    $newRoles[$i]->setNomRole("Mafioso");
                    $newRoles[$i]->setDescription("Un simple mafieu");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[6]);
                    break;
                case RolesEnum::ADMINISTRATEUR:
                    $newRoles[$i]->setNomRole("Administrateur");
                    $newRoles[$i]->setDescription("Le conseiller de monsieur le Dragon");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::IMPOSTEUR:
                    $newRoles[$i]->setNomRole("Imposteur");
                    $newRoles[$i]->setDescription("Pas de faux-semblant avec lui");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[8]);
                    break;
                case RolesEnum::TUEUR_A_GAGE:
                    $newRoles[$i]->setNomRole("Tueur à gage");
                    $newRoles[$i]->setDescription("Un simple membre de la Triade");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[9]);
                    break;
                case RolesEnum::FAUSSAIRE:
                    $newRoles[$i]->setNomRole("Faussaire");
                    $newRoles[$i]->setDescription("Quand on le jette d'un avion, on a un faussaire volant");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[8]);
                    break;
                case RolesEnum::MAITRE_ENCENS:
                    $newRoles[$i]->setNomRole("Maitre de l'encens");
                    $newRoles[$i]->setDescription("Dans la triade, le concierge ne nettoie pas, il parfume");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCrimeRole($newCrimes[7]);
                    $newRoles[$i]->addCategorieRole($newCategories[8]);
                    break;
                case RolesEnum::INFORMATEUR:
                    $newRoles[$i]->setNomRole("Informateur");
                    $newRoles[$i]->setDescription("Le nom Maître du déguisement étant copyrighté par la mafia, il a fallu s'adapter");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[8]);
                    $newRoles[$i]->addCategorieRole($newCategories[8]);
                    $newRoles[$i]->addCategorieRole($newCategories[9]);
                    break;
                case RolesEnum::INTERROGATEUR:
                    $newRoles[$i]->setNomRole("Interrogateur");
                    $newRoles[$i]->setDescription("Contrôle surprise !");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[5]);
                    $newRoles[$i]->addCategorieRole($newCategories[9]);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::LIAISON:
                    $newRoles[$i]->setNomRole("Liaison");
                    $newRoles[$i]->setDescription("Cette femme est plutôt nerveuse, mais reste très dangereuse");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[2]);
                    $newRoles[$i]->addCrimeRole($newCrimes[4]);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::MAITRE_SILENCE:
                    $newRoles[$i]->setNomRole("Maître du silence");
                    $newRoles[$i]->setDescription("1, 2 , 3 roi du silence !");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::AVANT_GARDE:
                    $newRoles[$i]->setNomRole("Avant-garde");
                    $newRoles[$i]->setDescription("Souvent mieux placé que l'arrière-garde...");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[10]);
                    break;
                case RolesEnum::AMNESIQUE:
                    $newRoles[$i]->setNomRole("Amnésique");
                    $newRoles[$i]->setDescription("Le pire, c'est que personne n'est fichu de retrouver ce certain Mnésique...");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[11]);
                    break;
                case RolesEnum::PYROMANE:
                    $newRoles[$i]->setNomRole("Pyromane");
                    $newRoles[$i]->setDescription("C'est un oiseau ! C'est un avion ! NON C'est Pyromane !");
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
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[6]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    break;
                case RolesEnum::CULTISTE:
                    $newRoles[$i]->setNomRole("Cultiste");
                    $newRoles[$i]->setDescription("Les gens du culte sont cultistes, ceux du aute sont autre chose...");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[0]);
                    $newRoles[$i]->addCrimeRole($newCrimes[2]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    break;
                case RolesEnum::BOURREAU:
                    $newRoles[$i]->setNomRole("Bourreau");
                    $newRoles[$i]->setDescription("Des fois il le fait bas, pour changer...");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[11]);
                    break;
                case RolesEnum::BOUFFON:
                    $newRoles[$i]->setNomRole("Bouffon");
                    $newRoles[$i]->setDescription("De bourreau à bouffon, il n'y a qu'un pas");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[11]);
                    break;
                case RolesEnum::TUEUR_DE_MASSE:
                    $newRoles[$i]->setNomRole("Tueur de masse");
                    $newRoles[$i]->setDescription("Quand un fou s'équipe d'une bombe, ça laisse des traces");
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
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCrimeRole($newCrimes[1]);
                    $newRoles[$i]->addCrimeRole($newCrimes[3]);
                    $newRoles[$i]->addCategorieRole($newCategories[12]);
                    $newRoles[$i]->addCategorieRole($newCategories[13]);
                    break;
                case RolesEnum::SURVIVANT:
                    $newRoles[$i]->setNomRole("Survivant");
                    $newRoles[$i]->setDescription("WILSOOOOOOOOON");
                    $newRoles[$i]->setRoleUnique(false);
                    $newRoles[$i]->addCategorieRole($newCategories[11]);
                    break;
                case RolesEnum::MARIONETTISTE:
                    $newRoles[$i]->setNomRole("Marionettiste");
                    $newRoles[$i]->setDescription("Pinocchion n'a qu'à bien se tenir !");
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

        $output->writeln("Creation de la composition officielle 15 joueurs");
        $newCompo = new Composition();
        $newCompo->setNomCompo("Officielle");
        $newCompo->setOfficielle(true);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::SHERIFF],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::SHERIFF]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::INSPECTEUR],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::INSPECTEUR]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::GARDIEN_DE_PRISON],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::GARDIEN_DE_PRISON]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::JUSTICIER],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::JUSTICIER]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::MAIRE],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::MAIRE]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::DOCTEUR],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::DOCTEUR]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::VETERAN],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::VETERAN]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::CITOYEN],2));
        $newCompo->addImportance($newImportanceBase[RolesEnum::CITOYEN]);

        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::PYROMANE],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::PYROMANE]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::BOURREAU],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::BOURREAU]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::SURVIVANT],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::SURVIVANT]);

        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::PARRAIN],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::PARRAIN]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::MAFIOSO],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::MAFIOSO]);
        $newCompo->addRoleCompo(new RolesCompos($newRoles[RolesEnum::CONSEILLER],1));
        $newCompo->addImportance($newImportanceBase[RolesEnum::CONSEILLER]);

        $em->persist($newCompo);
        $em->flush();
    }




} 