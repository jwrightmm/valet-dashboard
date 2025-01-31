<?php
$valet_xdg_home = getenv('HOME') . '/.config/valet';
$valet_old_home = getenv('HOME') . '/.valet';
$valet_home_path = is_dir($valet_xdg_home) ? $valet_xdg_home : $valet_old_home;
$valet_config = json_decode(file_get_contents("$valet_home_path/config.json"));
$tld = isset($valet_config->tld) ? $valet_config->tld : $valet_config->domain;
?>
<html>
    <title>Valet Dashboard</title>
    <head>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            li {
                margin-bottom:10px;
            }
            .site-list {
                display:none;
            }
            .site-list.list-2 {
                display:block;
            }
        </style>
    </head>
    <body class="m-8 font-sans">
        <div class="grid sm:grid-cols-[repeat(auto-fit,minmax(280px,1fr))] sm:justify-items-center">
            <?php $i = 0; ?>
            <?php foreach ($valet_config->paths as $parked_path) : ?>
                <?php $i++; ?>
                <div class="leading-normal whitespace-no-wrap m-2 site-list list-<?php echo $i; ?>">
                    <code class="font-mono text-gray-600"><?= str_replace(getenv('HOME'), '~', $parked_path) ?></code>
                    <ul>
                        <?php foreach (scandir($parked_path) as $site) : ?>
                            <?php if ($site == basename(__DIR__)): continue; endif ?>
                            <?php if ((is_dir("$parked_path/$site") || is_link("$parked_path/$site")) && $site[0] != '.') : ?>
                            <li><a href="http://<?= "$site.$tld" ?>/" target="<?= "valet_$site" ?>"
                                class="relative inline-block px-4 py-2 font-medium group">
                                <span class="absolute inset-0 w-full h-full transition duration-200 ease-out transform translate-x-1 translate-y-1 bg-black group-hover:-translate-x-0 group-hover:-translate-y-0"></span>
                                <span class="absolute inset-0 w-full h-full bg-white border-2 border-black group-hover:bg-black"></span>
                                <span class="relative text-black group-hover:text-white"><?= "$site.$tld" ?></span></a></li>
                            <?php endif ?>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>
        </div>
    </body>
</html>
