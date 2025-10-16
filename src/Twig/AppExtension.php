<?php

namespace App\Twig;

use App\Entity\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function getGlobals(): array
    {
        $cart = null;
        $user = $this->security->getUser();

        if ($user) {
            $cart = $this->em->getRepository(Cart::class)->findOneBy([
                'user' => $user,
                'status' => 'OPEN'
            ]);
        }

        return [
            'cart' => $cart,
        ];
    }
}
