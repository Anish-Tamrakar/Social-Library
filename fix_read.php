<?php
$file = "resources/views/books/read.blade.php";
$content = file_get_contents($file);

$search1 = "flipbook.append(`\n                <div class=\"hard cover-page bg-zinc-800 flex flex-col items-center justify-center p-12 text-center border-r border-zinc-700 shadow-[-10px_0_20px_rgba(0,0,0,0.3)oinset]\">";
$search2 = "totalFlipPages = totalPages + 4 + (addedPad ? 1 : 0);";

$start = strpos($content, "flipbook.append(`\n                <div class=\"hard cover-page");
$end = strpos($content, "totalFlipPages = totalPages + 4 + (addedPad ? 1 : 0);");

if ($start !== false && $end !== false) {
    $endLength = strlen("totalFlipPages = totalPages + 4 + (addedPad ? 1 : 0);");
    $block = substr($content, $start, $end + $endLength - $start);
    $newBlock = "let htmlBuffer = `\n                <div class=\"hard cover-page bg-zinc-800 flex flex-col items-center justify-center p-12 text-center border-r border-zinc-700 shadow-[-10px_0_20px_rgba(0,0,0,0.3)_inset]\">\n                    <div class=\"flex-1 flex flex-col justify-center\">\n                        <h1 class=\"text-3xl md:text-5xl font-serif font-bold text-white mb-6 leading-tight\">\${bookTitle}</h1>\n                        <p class=\"text-xl text-zinc-400 font-medium tracking-wide border-t border-zinc-600 pt-6 mt-2 inline-block mx-auto min-w-[50%]\">\${bookAuthor}</p>\n                    </div>\n                </div>\n            `;\n\n            htmlBuffer += `<div class=\"hard bg-[#fcfcfa] shadow-[10px_0_20px_rgba(0,0,0,0.05)_inset]\"></div>`;\n\n            // Document Pages\n            for (let i = 1; i <= totalPages; i++) {\n                const flipbookPageNum = i + 2; \n                let shadowClass = \"\";\n                if (flipbookPageNum % 2 === 0) {\n                    shadowClass = \"right-0 bg-gradient-to-l from-black/20 to-transparent\";\n                } else {\n                    shadowClass = \"left-0 bg-gradient-to-r from-black/20 to-transparent\";\n                }\n\n                htmlBuffer += `\n                    <div class=\"page-wrapper bg-[#fcfcfa]\">\n                        <div id=\"page-container-\${i}\" class=\"w-full h-full flex flex-col items-center justify-center relative bg-[#ffffff] text-zinc-400 overflow-hidden\">\n                            <div class=\"absolute inset-y-0 w-16 pointer-events-none z-10 mix-blend-multiply opacity-50 \${shadowClass}\"></div>\n                            <svg class=\"w-8 h-8 opacity-20 mb-2\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"1.5\" d=\"M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253\"></path></svg>\n                        </div>\n                    </div>\n                `;\n            }\n\n            let addedPad = false;\n            if (totalPages % 2 !== 0) {\n                htmlBuffer += `\n                    <div class=\"page-wrapper bg-[#fcfcfa]\">\n                        <div class=\"w-full h-full relative bg-[#ffffff]\">\n                            <div class=\"absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-black/20 to-transparent pointer-events-none z-10 mix-blend-multiply opacity-50\"></div>\n                        </div>\n                    </div>\n                `;\n                addedPad = true;\n            }\n\n            htmlBuffer += `<div class=\"hard bg-[#fcfcfa] shadow-[-10px_0_20px_rgba(0,0,0,0.05)_inset]\"></div>`;\n            htmlBuffer += `<div class=\"hard cover-page bg-zinc-800 border-l border-zinc-700 shadow-[10px_0_20px_rgba(0,0,0,0.3)_inset] flex items-center justify-center\"><div class=\"w-32 h-32 opacity-10 bg-zinc-500 rounded-full flex items-center justify-center\"><div class=\"w-24 h-24 bg-zinc-800 rounded-full border-4 border-zinc-500\"></div></div></div>`;\n            \n            flipbook.html(htmlBuffer);\n            totalFlipPages = totalPages + 4 + (addedPad ? 1 : 0);";
    
    $content = str_replace($block, $newBlock, $content);
    file_put_contents($file, $content);
    echo "Success!";
} else {
    echo "Fail: block not found";
}

