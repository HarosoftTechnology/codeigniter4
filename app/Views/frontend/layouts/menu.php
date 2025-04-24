<ul class="mt-6">
            <?php foreach (get_menus("admin-menu") as $menu): ?>
                <li class="relative px-6 py-3 <?= ($menu->isActive()) ? 'active-menu' : '' ?> <?= ($menu->hasChildren() && $menu->isActive()) ? 'open' : '' ?>">
                    <a href="<?= ($menu->hasChildren()) ? 'javascript:void(0);' : $menu->link ?>" class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100" <?= ($menu->hasChildren()) ? '@click="togglePagesMenu"' : '' ?>  aria-haspopup="true">
                        <span class="inline-flex items-center">
                            <i class="<?= $menu->icon ?>"></i>
                            <span class="ml-4"><?= $menu->title ?></span>
                        </span>
                        <?php if($menu->hasChildren()): ?>
                            <i class="fa fa-angle-down ml-1"></i>
                        <?php endif ?>
                    </a>
                    <?php if ($menu->hasChildren()): ?>
                        <template x-if="isPagesMenuOpen">
                            <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                                <?php foreach ($menu->children as $subMenu): ?>
                                    <li class="<?= ($subMenu->isActive()) ? 'active-menu' : '' ?> px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                        <a href="<?= ($subMenu->hasChildren()) ? 'javascript:void(0);' : $subMenu->link ?>" class="w-full" <?= ($subMenu->hasChildren()) ? '@click="togglePagesMenu"' : '' ?>  aria-haspopup="true"><?= $subMenu->title ?></a>
                                    </li>
                                    <?php if ($subMenu->hasChildren()): ?>
                                        <ul x-transition:enter="transition-all ease-in-out duration-300" x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl" x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0" class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900" aria-label="submenu">
                                            <?php foreach ($subMenu->children as $sMenu): ?>
                                                <li class="<?= ($subMenu->isActive()) ? 'active-menu' : '' ?> px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                                    <a href="<?= $sMenu->link ?>" class="w-full"><?= $sMenu->title ?></a>
                                                </li>
                                            <?php endforeach ?>
                                        </ul>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </ul>
                        </template>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>