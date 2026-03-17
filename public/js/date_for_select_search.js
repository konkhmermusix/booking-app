document.addEventListener("DOMContentLoaded", function () {
    const checkInInput = document.getElementById("check_in");
    const checkOutInput = document.getElementById("check_out");

    // ១. កំណត់ឱ្យថ្ងៃចូលស្នាក់នៅ រើសបានចាប់ពី "ថ្ងៃនេះ" ឡើងទៅ
    const today = new Date().toISOString().split("T")[0];
    checkInInput.setAttribute("min", today);

    // ២. នៅពេលអ្នកប្រើប្រាស់រើសថ្ងៃចូលស្នាក់នៅរួច
    checkInInput.addEventListener("change", function () {
        const checkInDate = this.value;

        // កំណត់ថ្ងៃ "ទាបបំផុត" នៃថ្ងៃចាកចេញ ឱ្យស្មើនឹងថ្ងៃចូលស្នាក់នៅ + ១ថ្ងៃ
        if (checkInDate) {
            let nextDay = new Date(checkInDate);
            nextDay.setDate(nextDay.getDate() + 1);
            const minCheckOut = nextDay.toISOString().split("T")[0];

            checkOutInput.setAttribute("min", minCheckOut);

            // ប្រសិនបើថ្ងៃចាកចេញដែលរើសហើយ ទាបជាងថ្ងៃចូល ឱ្យវារើសថ្មី
            if (checkOutInput.value && checkOutInput.value <= checkInDate) {
                checkOutInput.value = minCheckOut;
            }
        }
    });

    // បើកទំព័រមកដំបូង ឆែកលក្ខខណ្ឌភ្លាម (ករណីមាន value ចាស់)
    if (checkInInput.value) {
        checkInInput.dispatchEvent(new Event("change"));
    }
});
