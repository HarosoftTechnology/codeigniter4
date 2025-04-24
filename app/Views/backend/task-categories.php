<main class="h-full overflow-y-auto">
    <div class="container px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Task Categories</h2>
    
    <div class="flex justify-end mb-2">
        <a href="<?= url_to_pager('create-task-category') ?>" class="px-4 py-2 font-medium leading-5 text-white transition-colors bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <i class="fa fa-plus"></i> Create Category 
        </a>
    </div>
    
    <!-- New Table -->
    <div class="w-1/2 overflow-hidden rounded-lg shadow-xs mx-auto">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3">S/N</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3 text-center">Edit</th>
                    <th class="px-4 py-3 text-center">Delete</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <?php $i = 1 ?>
                    <?php foreach($categories as $category): ?>
                        <tr id="item-<?= $category['id'] ?>" class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3" style="width: 3%;"><?= $i++ ?></td>
                            <td class="px-4 py-3"><?= $category['name'] ?></td>
                            <td class="px-4 py-3 text-center"><a href="javascript:;" onclick="window.location.replace('<?= base_url('admincp/task/category/update/'.$category['id']) ?>')" class="text-green-500"><fa class="fa fa-pen"></fa></a></td>
                            <td class="px-4 py-3 text-center text-red-500"><a href="javascript:;"  onclick="delete_task_category('<?= $category['id'] ?>')" class="text-red-500"><fa class="fa fa-trash"></fa></a></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        
    </div>


    </div>
</main>