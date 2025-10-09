# 廠商請款追蹤系統 (Vendor Payment Tracking System)

這是一個使用 PHP 和 MySQL 開發的網頁應用程式，旨在幫助使用者追蹤和管理對各個廠商的請款流程。

## 功能

- **智慧儀表板**: 快速概覽本月應付總額、發票狀態和流程進度。
- **每月請款作業**: 集中管理每個月的請款項目，更新進度。
- **作業日曆**: 以日曆視圖顯示所有重要的請款日期。
- **費用清單**: 搜尋和篩選歷史請款紀錄。
- **廠商管理**: 新增、編輯和管理合作廠商的資訊。
- **報表與分析**: 提供視覺化圖表分析費用趨勢。
- **系統設定**: 自定義請款抬頭和通知設定。

## 技術棧

- **前端**: HTML, Tailwind CSS, JavaScript (Vanilla)
- **後端**: PHP
- **資料庫**: MySQL
- **伺服器環境**: XAMPP (或任何支援 PHP 和 MySQL 的環境)

## 專案結構

```
/
|-- css/
|   |-- style.css           # 自定義樣式
|-- js/
|   |-- app.js              # 前端應用程式邏輯
|-- php/
|   |-- api.php             # 後端 API 端點
|   |-- db_connect.php      # 資料庫連線設定
|-- index.php               # 主要應用程式檔案 (HTML 結構)
|-- database.sql            # 資料庫結構 (Schema)
|-- README.md               # 專案說明文件
```

## 安裝與執行

1.  **安裝 XAMPP**:
    -   前往 [XAMPP 官方網站](https://www.apachefriends.org/index.html) 下載並安裝適合您作業系統的版本。

2.  **啟動 Apache 和 MySQL**:
    -   打開 XAMPP 控制面板，啟動 Apache 和 MySQL 模組。

3.  **複製專案檔案**:
    -   將本專案的所有檔案複製到 XAMPP 的 `htdocs` 資料夾中。在 Windows 上，通常是 `C:\xampp\htdocs\`。您可以將專案放在 `htdocs` 下的一個子資料夾中，例如 `htdocs\payment-tracker\`。

4.  **建立資料庫**:
    -   打開瀏覽器，前往 `http://localhost/phpmyadmin/`。
    -   點擊 "New" 來建立一個新的資料庫。
    -   資料庫名稱請輸入 `payment_tracker`，並點擊 "Create"。

5.  **匯入資料庫結構**:
    -   選擇您剛剛建立的 `payment_tracker` 資料庫。
    -   點擊 "Import" 頁籤。
    -   點擊 "Choose File" 並選擇本專案中的 `database.sql` 檔案。
    -   點擊 "Go" 開始匯入。

6.  **瀏覽應用程式**:
    -   打開瀏覽器，前往 `http://localhost/your-project-folder/` (例如 `http://localhost/payment-tracker/`) 即可看到應用程式。

---
This README provides clear instructions for setting up and running the application.