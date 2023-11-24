#!/usr/bin/perl
use strict;
use warnings;
use Term::ANSIColor;

# Ligne horizontale de * pour le titre
my $title_line = "*" x 80;
my $title = "*                         Informatique - ASV PACKAGING                         *";

# Messages
my $message1 = "*                                                                              *";
my $message2 = "*       Permet l'optimisation de la mise en production de l'inventaire         *";

# Ligne horizontale de * pour le bas
my $bottom_line = "*" x 80;

# Affichage
print color("green"), $title_line, color('reset'), "\n";
print color("green"), $title, color('reset'), "\n";
print color("green"), $title_line, color('reset'), "\n";
print color("green"), $message1, color('reset'), "\n";
print color("green"), $message2, color('reset'), "\n";
print color("green"), "*                                                                              *", color('reset'), "\n";
print color("green"), $bottom_line, color('reset'), "\n";

my $reponse;

print "Voulez-vous installer toutes les dépendances (composer install) ? (y/n): ";
$reponse = lc(<STDIN>);
chomp $reponse;

if ($reponse eq 'y' || $reponse eq '') {
    system("cd /var/www/inventaire ; composer install > /dev/null 2>&1");

    if ($? == 0) {
        print color("green"), "Les dépendances ont bien été installées.", color('reset'), "\n";
    } else {
        print color("red"), "Une erreur est survenue.", color('reset'), "\n";}
    }

print "Voulez-vous mettre à jour toutes les dépendances (composer update) ? (y/n): ";
$reponse = lc(<STDIN>);
chomp $reponse;

if ($reponse eq 'y' || $reponse eq '') {
    system("cd /var/www/inventaire ; composer update > /dev/null 2>&1");

    if ($? == 0) {
        print color("green"), "Les dépendances ont bien été mises à jour.", color('reset'), "\n";
    } else {
        print color("red"), "Une erreur est survenue.", color('reset'), "\n";
    }
}

print "Voulez-vous faire une migration de la base de données ? (y/n): ";
$reponse = lc(<STDIN>);
chomp $reponse;

if ($reponse eq 'y' || $reponse eq '') {
    system("cd /var/www/inventaire ; php bin/console doctrine:migrations:migrate");

    if ($? != 0) {
        print color("red"), "Une erreur est survenue.", color('reset'), "\n";
    }
}

print color("cyan"), "Suppression du cache en cours...", color('reset'), "\n";
system("cd /var/www/inventaire ; php bin/console cache:clear --env=prod > /dev/null 2>&1");
system("cd /var/www/inventaire ; php bin/console cache:clear --env=dev > /dev/null 2>&1");
system("cd /var/www/inventaire/var/cache ; rm -r dev > /dev/null 2>&1");

if ($? == 0) {
    print color("green"), "Le cache a bien été supprimé.", color('reset'), "\n";
} else {
    print color("red"), "Une erreur est survenue.", color('reset'), "\n";
}