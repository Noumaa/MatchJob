<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class OfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offer::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
//            Field::new('id'), FIXME: NOT IN FORM
            Field::new('label', "Nom"),
            MoneyField::new('moneyPerHour', "Salaire/heure")->setCurrency("EUR"),
            Field::new('description', "Contenu"),
//            Field::new('duration'),
            Field::new('startAt', "Débute le"),
            Field::new('endAt', "Termine le"),
            Field::new('createdAt', "Créée le")->hideOnForm(),
//            AssociationField::new('user'), FIXME: NOT IN FORM
            Field::new('isArchived', "Supprimée ?"),
//            AssociationField::new('demands'),
        ];
    }
}
