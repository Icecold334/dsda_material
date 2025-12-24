<div class="bg-white p-4 rounded-lg overflow-x-auto">
    <div id="wrapper"></div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    new Grid({
        columns: ["Name", "Email", "Phone"],
        data: [
            ["John", "john@mail.com", "08123"],
            ["Sarah", "sarah@mail.com", "08999"],
        ],
        pagination: {
            limit: 5
        },
        search: true,
        sort: true
    }).render(document.getElementById("wrapper"));
});
</script>