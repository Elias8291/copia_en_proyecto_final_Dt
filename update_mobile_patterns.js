// Script to update remaining mobile patterns in the blade file
const fs = require('fs');

const filePath = 'resources/views/revision/revisar-datos.blade.php';
let content = fs.readFileSync(filePath, 'utf8');

// Update all remaining review panel headers
content = content.replace(
    /class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-100 rounded-t-lg"/g,
    'class="flex items-center justify-between px-3 sm:px-4 py-2.5 sm:py-3 bg-gray-50 border-b border-gray-100 rounded-t-lg"'
);

// Update all remaining comment boxes
content = content.replace(
    /class="px-4 py-3 bg-blue-50 border-l-3 border-blue-400"/g,
    'class="px-3 sm:px-4 py-2.5 sm:py-3 bg-blue-50 border-l-3 border-blue-400"'
);

// Update all remaining control sections
content = content.replace(
    /class="p-4 space-y-3"/g,
    'class="p-3 sm:p-4 space-y-3"'
);

// Update all remaining button containers
content = content.replace(
    /class="flex items-center justify-center space-x-2"/g,
    'class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center space-y-2 sm:space-y-0 sm:space-x-2"'
);

// Update all remaining buttons
content = content.replace(
    /class="inline-flex items-center px-3 py-1\.5 bg-white border border-emerald-300 text-emerald-700 rounded-md font-medium text-xs hover:bg-emerald-50 focus:outline-none focus:ring-1 focus:ring-emerald-400 transition-colors"/g,
    'class="inline-flex items-center justify-center px-3 py-2 sm:py-1.5 bg-white border border-emerald-300 text-emerald-700 rounded-md font-medium text-xs hover:bg-emerald-50 focus:outline-none focus:ring-1 focus:ring-emerald-400 transition-colors"'
);

content = content.replace(
    /class="inline-flex items-center px-3 py-1\.5 bg-white border border-red-300 text-red-700 rounded-md font-medium text-xs hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-400 transition-colors"/g,
    'class="inline-flex items-center justify-center px-3 py-2 sm:py-1.5 bg-white border border-red-300 text-red-700 rounded-md font-medium text-xs hover:bg-red-50 focus:outline-none focus:ring-1 focus:ring-red-400 transition-colors"'
);

// Update all remaining textareas
content = content.replace(
    /rows="2"/g,
    'rows="3"'
);

content = content.replace(
    /class="block w-full px-3 py-2 border border-gray-300 rounded-md text-xs placeholder-gray-400 focus:ring-1 focus:ring-\[#9D2449\] focus:border-\[#9D2449\] focus:outline-none resize-none"/g,
    'class="block w-full px-3 py-2 pr-16 sm:pr-20 border border-gray-300 rounded-md text-xs placeholder-gray-400 focus:ring-1 focus:ring-[#9D2449] focus:border-[#9D2449] focus:outline-none resize-none"'
);

// Update all remaining save buttons in textareas
content = content.replace(
    /<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"\s+viewBox="0 0 24 24">\s+<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"\s+d="M5 13l4 4L19 7" \/>\s+<\/svg>\s+Guardar/g,
    `<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span class="hidden sm:inline">Guardar</span>
                                            <span class="sm:hidden">OK</span>`
);

// Update all remaining icon margins in buttons
content = content.replace(
    /<svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"/g,
    '<svg class="w-3 h-3 mr-1.5 sm:mr-1" fill="none" stroke="currentColor"'
);

// Update all remaining status badges
content = content.replace(
    /<div class="w-2 h-2 bg-amber-400 rounded-full mr-1\.5"><\/div>\s+Pendiente/g,
    `<div class="w-2 h-2 bg-amber-400 rounded-full mr-1.5"></div>
                                    <span class="hidden sm:inline">Pendiente</span>
                                    <span class="sm:hidden">Pend.</span>`
);

// Update all remaining comment text containers
content = content.replace(
    /class="flex-1">/g,
    'class="flex-1 min-w-0">'
);

content = content.replace(
    /class="text-xs text-blue-700"><\/p>/g,
    'class="text-xs text-blue-700 break-words"></p>'
);

// Update all remaining icon containers
content = content.replace(
    /class="w-6 h-6 bg-slate-600 rounded-md flex items-center justify-center"/g,
    'class="w-5 h-5 sm:w-6 sm:h-6 bg-slate-600 rounded-md flex items-center justify-center flex-shrink-0"'
);

content = content.replace(
    /class="text-sm font-medium text-gray-700"/g,
    'class="text-xs sm:text-sm font-medium text-gray-700"'
);

// Update all remaining icon sizes in comment boxes
content = content.replace(
    /class="w-4 h-4 text-blue-500"/g,
    'class="w-3 h-3 sm:w-4 sm:h-4 text-blue-500"'
);

fs.writeFileSync(filePath, content);
console.log('Mobile patterns updated successfully!');