# 購票系統需求規格

## 1. 資料庫設計

### Table: users
| 欄位名稱           | 型態         | 說明           |
|--------------------|--------------|----------------|
| id                 | bigint       | 主鍵           |
| name               | string       | 使用者名稱     |
| email              | string       | Email          |
| password           | string       | 密碼           |
| email_verified_at  | timestamp    | 驗證時間       |
| remember_token     | string       | Token          |
| created_at         | timestamp    | 建立時間       |
| updated_at         | timestamp    | 更新時間       |

### Table: tickets
| 欄位名稱     | 型態      | 說明         |
|--------------|-----------|--------------|
| id           | bigint    | 主鍵         |
| name         | string    | 票券名稱     |
| total_number | integer   | 總數量       |
| sold         | integer   | 已售出數量   |
| created_at   | timestamp | 建立時間     |
| updated_at   | timestamp | 更新時間     |

### Table: orders
| 欄位名稱     | 型態      | 說明         |
|--------------|-----------|--------------|
| id           | bigint    | 主鍵         |
| user_id      | bigint    | 使用者ID     |
| ticket_id    | bigint    | 票券ID       |
| number       | integer   | 購買張數     |
| created_at   | timestamp | 建立時間     |
| updated_at   | timestamp | 更新時間     |

---

## 2. 頁面設計

- **登入頁**
  - 欄位：Email、密碼
  - 按鈕：登入

- **購票頁**
  - 顯示票券名稱、剩餘數量
  - 欄位：購買張數
  - 按鈕：購買

---

## 3. Seeder 設計

- 產生 5000 個使用者
- 產生 2 張票券
- orders 預設為空

---

## 4. 功能流程

1. 使用者登入
2. 進入購票頁，選擇票券與購買張數
3. 按下購買，系統檢查剩餘票數
4. 若足夠則建立訂單並更新已售出數量
5. 顯示購買成功或失敗訊息

---

# Acceptance Criteria（驗收標準）

1. **資料庫結構**
   - [ ] users、tickets、orders 三個資料表皆正確建立，欄位齊全
2. **Seeder**
   - [ ] 執行 seeder 後，users 表有 5000 筆資料
   - [ ] tickets 表有 2 筆資料
   - [ ] orders 表預設為空
3. **登入功能**
   - [ ] 使用者可用 email/password 成功登入
   - [ ] 登入失敗時有錯誤提示
4. **購票頁面**
   - [ ] 登入後可看到所有票券資訊（名稱、剩餘數量）
   - [ ] 可輸入購買張數並按下購買
   - [ ] 若購買張數超過剩餘數量，顯示錯誤訊息
   - [ ] 購買成功後，orders 表新增一筆資料，tickets 的 sold 正確更新
5. **驗證**
   - [ ] 未登入者無法進入購票頁
   - [ ] 購買張數必須大於 0
6. **UI**
   - [ ] 只有兩個頁面：登入、購票
   - [ ] 購票頁只有一個購買按鈕與一個張數欄位
