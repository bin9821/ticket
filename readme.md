# Ticket (Laravel12)

本專案包含 JMeter 壓力測試腳本與測試所需的使用者資料，用於模擬大量用戶同時搶票的情境。

---

## 專案內容

**all_user.csv**
  - 測試用帳號清單，供 JMeter 讀取登入使用
**ticket stress test.jmx**
  - JMeter 壓力測試腳本，用於模擬多用戶同時購票
**重置票券數量API**
  - /api/ticket/resetAllTickets，method為POST
  - method為POST；重置票券不需登入，便於壓力測試多次循環。
---

## 使用方法

### 1. 匯入 JMeter 測試腳本
打開 JMeter → `File` → `Open` → 選擇 `ticket stress test.jmx`
### 2. 設定執行參數
可以調整：
- Threads (模擬使用者數量)
- Ramp-Up (同時併發的速度)
- Loop Count (重複次數)
### 3. 開始壓力測試
點擊 **Start** ▶ 執行測試。
