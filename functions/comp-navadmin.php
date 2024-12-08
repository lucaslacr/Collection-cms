<?php

$translationNs = array(
    array(
        "lang" => "fr",
        "title" => "Modifier le site",
        "page" => "Pages",
        "media" => "Médias",
        "announcement" => "Annonces",
        "setting" => "Paramètres",
        "component" => "Composants",
        "form" => "Formulaires",
    ),
    array(
        "lang" => "en",
        "title" => "Connect to your database",
        "description" => "Fill the form with your database information. <br> You can find them with our web hoster.",
        "namedb" => "Database name",
        "hostdb" => "Host (address)",
        "hostindication" => "'localhost' in most of case",
        "userdbindication" => "Check if your database user have the right to edit database",
        "userdb" => "Database user",
        "passworddb" => "Database password",
        "connect" => "Connect to database"
    )
);
$translationN = null;
foreach ($translationNs as $t) {
    if ($t["lang"] == $lang) {
        $translationN = $t;
        break;
    }
}
?>
<style>
    nav.admin li a {
        display: flex;
        gap: 6px;
        flex-direction: row;
        border-bottom: 0;
        align-items: center;
    }

    nav.admin ul {
        display: flex;
        gap: 12px;
        flex-direction: column;
    }

    .icon-admin img {
        height: 28px;
        width: 28px;
        margin: 0px;
    }

    nav.admin li::marker {
        content: '';
    }

    .islight {
        display: block;
    }

    .isdark {
        display: none !important;
    }

    @media (prefers-color-scheme: dark) {

        .islight {
            display: none !important;
        }

        .isdark {
            display: block !important;
        }
    }
</style>
<header>
    <div class="logo-collection">
        <img class="isdark" src="<?= $access ?>admin-assets/logo-collection-d.svg" alt="Collection cms" />
        <img class="islight" src="<?= $access ?>admin-assets/logo-collection-l.svg" alt="Collection cms" />
    </div>

    <nav class="admin">
        <ul>
            <li><a href="<?= $access ?>pages">
                    <div class="icon-admin">
                        <img class="isdark" src="<?= $access ?>admin-assets/page-d.svg" alt />
                        <img class="islight" src="<?= $access ?>admin-assets/page-l.svg" alt />
                    </div>
                    <?= $translationN['page'] ?>
                </a></li>
            <li><a href="<?= $access ?>medias">
                    <div class="icon-admin">
                        <img class="isdark" src="<?= $access ?>admin-assets/media-d.svg" alt />
                        <img class="islight" src="<?= $access ?>admin-assets/media-l.svg" alt />
                    </div>
                    <?= $translationN['media'] ?>
                </a></li>
            <li><a href="<?= $access ?>announcement">
                    <div class="icon-admin">
                        <img class="isdark" src="<?= $access ?>admin-assets/announcement-d.svg" alt />
                        <img class="islight" src="<?= $access ?>admin-assets/announcement-l.svg" alt />
                    </div>
                    <?= $translationN['announcement'] ?>
                </a></li>
            <li><a href="<?= $access ?>components">
                    <div class="icon-admin">
                        <img class="isdark" src="<?= $access ?>admin-assets/component-d.svg" alt />
                        <img class="islight" src="<?= $access ?>admin-assets/component-l.svg" alt />
                    </div>
                    <?= $translationN['component'] ?>
                </a></li>
            <li><a href="<?= $access ?>forms">
                    <div class="icon-admin">
                        <img class="isdark" src="<?= $access ?>admin-assets/form-d.svg" alt />
                        <img class="islight" src="<?= $access ?>admin-assets/form-l.svg" alt />
                    </div>
                    <?= $translationN['form'] ?>
                </a></li>
            <div aria-hidden="true" style="height: 24px;"></div>
            <li><a href="<?= $access ?>settings">
                    <div class="icon-admin">
                        <img class="isdark" src="<?= $access ?>admin-assets/setting-d.svg" alt />
                        <img class="islight" src="<?= $access ?>admin-assets/setting-l.svg" alt />
                    </div>
                    <?= $translationN['setting'] ?>
                </a></li>
        </ul>
    </nav>
</header>