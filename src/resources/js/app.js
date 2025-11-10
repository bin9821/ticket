import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// HELLO button handler
function initializeHelloButton() {
    const helloButton = document.getElementById('hello-btn');
    if (helloButton) {
        helloButton.addEventListener('click', () => {
            alert('HELLO');
        });
    }
}

window.showReadMe = function () {
    alert("Read Me:\n" +
        "高併發測試時，\n可使用CSV下載的信箱資料進行測試，\n密碼皆為password\n" +
        "購票的接口為/ticket/purchase/1，method為post\n" +
        "恢復所有票券數量為/api/ticket/resetAllTickets，method為post\n" +
        "購票需要登陸後才能購票，恢復票券則不用登陸"
    );
};



if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeHelloButton);
} else {
    initializeHelloButton();
}
