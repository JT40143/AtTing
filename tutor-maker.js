document.addEventListener('DOMContentLoaded', () => {

    // --- DOM 元素獲取 ---
    const mainTitleInput = document.getElementById('main-title');
    const mainSubtitleInput = document.getElementById('main-subtitle');
    const editorContainer = document.getElementById('editor-container');
    const addBlockBtn = document.getElementById('add-block-btn');
    const generateBtn = document.getElementById('generate-btn');
    const blockTemplate = document.getElementById('block-template');

    // --- 事件監聽器 ---

    // 點擊 "新增區塊" 按鈕
    addBlockBtn.addEventListener('click', () => {
        addBlock();
    });

    // 點擊 "產出網頁" 按鈕
    generateBtn.addEventListener('click', () => {
        generatePage();
    });

    // 使用事件委派來處理動態新增的區塊中的事件
    editorContainer.addEventListener('click', (e) => {
        // 如果點擊的是刪除按鈕
        if (e.target.classList.contains('delete-block-btn')) {
            const block = e.target.closest('.block');
            if (block) {
                block.remove();
            }
        }
    });

    editorContainer.addEventListener('change', (e) => {
        // 如果是圖片上傳 input 發生變化
        if (e.target.classList.contains('block-image-input')) {
            handleImageUpload(e);
        }
    });

    // --- 核心功能函數 ---

    /**
     * 從模板複製並新增一個教學區塊到編輯器中
     */
    function addBlock() {
        const blockClone = blockTemplate.content.cloneNode(true);
        editorContainer.appendChild(blockClone);
    }

    /**
     * 處理圖片上傳並顯示預覽
     * @param {Event} e - change 事件對象
     */
    function handleImageUpload(e) {
        const input = e.target;
        const file = input.files[0];
        const preview = input.nextElementSibling; // 獲取 <img class="image-preview">

        if (file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }

    /**
     * 產生最終的教學網頁
     */
    function generatePage() {
        const mainTitle = mainTitleInput.value.trim();
        const mainSubtitle = mainSubtitleInput.value.trim();
        const blocks = editorContainer.querySelectorAll('.block');

        // 收集所有區塊的資料
        const blocksData = [];
        blocks.forEach(block => {
            const blockTitle = block.querySelector('.block-title').value.trim();
            const blockContent = block.querySelector('.block-content').value.trim();
            const imagePreview = block.querySelector('.image-preview');
            const imgSrc = imagePreview.style.display !== 'none' ? imagePreview.src : '';

            blocksData.push({
                title: blockTitle,
                content: blockContent,
                imgSrc: imgSrc
            });
        });

        // 建立 HTML 內容
        const outputHtml = createOutputHtml(mainTitle, mainSubtitle, blocksData);

        // 在新分頁中開啟並寫入內容
        const outputWindow = window.open('', '_blank');
        if (outputWindow) {
            outputWindow.document.open();
            outputWindow.document.write(outputHtml);
            outputWindow.document.close();
        } else {
            alert('彈出視窗被阻擋，請允許彈出視窗以查看生成的網頁。');
        }
    }

    /**
     * 根據使用者輸入的資料建立最終的 HTML 字串
     * @param {string} mainTitle - 大標題
     * @param {string} mainSubtitle - 副標題
     * @param {Array} blocksData - 包含所有區塊資料的陣列
     * @returns {string} - 完整的 HTML 頁面字串
     */
    function createOutputHtml(mainTitle, mainSubtitle, blocksData) {
        // 建立每個區塊的 HTML
        const blocksHtml = blocksData.map(data => {
            // 將內文中的換行符號轉換為 <br>
            const formattedContent = data.content.replace(/\n/g, '<br>');

            return `
                <div class="output-block">
                    <h2 class="output-block-title">${data.title || ''}</h2>
                    <p class="output-block-content">${formattedContent || ''}</p>
                    ${data.imgSrc ? `<img src="${data.imgSrc}" alt="${data.title}" class="output-block-image">` : ''}
                </div>
            `;
        }).join('');

        // 獲取最終頁面的 CSS 樣式
        const outputCss = getOutputCss();

        // 組合成完整的 HTML 檔案
        return `
            <!DOCTYPE html>
            <html lang="zh-Hant-TW">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>${mainTitle || '教學頁面'}</title>
                <style>
                    ${outputCss}
                </style>
            </head>
            <body class="output-body">
                <div class="output-container">
                    <h1 class="output-main-title">${mainTitle || ''}</h1>
                    <p class="output-main-subtitle">${mainSubtitle || ''}</p>
                    ${blocksHtml}
                </div>
            </body>
            </html>
        `;
    }

    /**
     * 定義並返回最終輸出頁面所需的 CSS 樣式
     * @returns {string} - CSS 樣式字串
     */
    function getOutputCss() {
        return `
            .output-body {
                background-color: #f0f2f5;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                padding: 40px 20px;
                display: flex;
                flex-direction: column;
                align-items: center;
                margin: 0;
            }

            .output-container {
                max-width: 700px;
                width: 100%;
            }

            .output-main-title {
                font-size: 40px;
                font-weight: bold;
                color: #333;
                text-align: center;
                margin-bottom: 8px;
            }

            .output-main-subtitle {
                font-size: 18px;
                color: #888;
                text-align: center;
                margin-bottom: 40px;
            }

            .output-block {
                background-color: #ffffff;
                border-radius: 16px;
                padding: 30px;
                margin-bottom: 20px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            }

            .output-block-title {
                font-size: 24px;
                font-weight: bold;
                color: #007bff; /* 藍色標題 */
                margin-bottom: 12px;
            }

            .output-block-content {
                font-size: 16px;
                color: #555;
                line-height: 1.6;
                margin-bottom: 20px;
                white-space: pre-wrap; /* 保留換行和空格 */
            }

            .output-block-image {
                width: 100%;
                border-radius: 8px;
                border: 1px solid #eee;
                margin-top: 10px;
            }
        `;
    }

    // --- 初始化 ---
    // 頁面載入時先自動新增一個空白區塊，方便使用者開始
    addBlock();
});