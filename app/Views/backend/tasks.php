<main class="w-full h-full overflow-y-auto">
    <div class="container px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Tasks</h2>
    
    <div class="flex justify-end mb-2">
        <a href="<?= url_to_pager('create-task') ?>" class="px-4 py-2 font-medium leading-5 text-white transition-colors bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            <i class="fa fa-plus"></i> Create Task
        </a>
    </div>
    
    <div class="mb-4 relative text-gray-500 focus-within:text-purple-600 sm:w-full md:w-50 lg:w-1/3">
        <input id="searchInput" onkeyup="filterTask()" class="block w-full pr-20 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="Search tasks">
        <button class="absolute inset-y-0 right-0 px-4 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
        Seach
        </button>
    </div>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
        <table id="tasktable" class="w-full whitespace-no-wrap">
            <thead>
            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">S/N</th>
                <th class="px-4 py-3">Title</th>
                <th class="px-4 py-3">Category</th>
                <th class="px-4 py-3">Deadline</th>
                <th class="px-4 py-3 text-center">Edit</th>
                <th class="px-4 py-3 text-center">Delete</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                <?php $i = 1 ?>
                <?php foreach($tasks as $task): ?>
                    <tr id="item-<?= $task['id'] ?>" class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3" style="width: 3%;"><?= $i++ ?></td>
                        <td class="px-4 py-3"><?= $task['title'] ?></td>
                        <td class="px-4 py-3 text-sm"><?= $task['category'] ?></td>
                        <td class="px-4 py-3 text-sm"><?= $task['deadline'] ?></td>
                        <td class="px-4 py-3 text-center"><a href="javascript:;" onclick="window.location.replace('<?= base_url('admincp/task/update/'.$task['id']) ?>')" class="text-green-500"><fa class="fa fa-pen"></fa></a></td>
                        <td class="px-4 py-3 text-center text-red-500"><a href="javascript:;"  onclick="delete_task(<?= $task['id'] ?>)" class="text-red-500"><fa class="fa fa-trash"></fa></a></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        </div>
        
    </div>

    </div>
</main>
