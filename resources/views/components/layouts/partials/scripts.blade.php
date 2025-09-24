{{-- sidebar --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('sidebar-toggle');

        toggleButton.addEventListener('click', function () {
            if (sidebar.classList.contains('w-18')) {
                sidebar.classList.remove('w-18');
                sidebar.classList.add('w-80');
                document.querySelectorAll('.sidebar-label').forEach(label => {
                    label.classList.remove('hidden');
                });
            } else {
                sidebar.classList.remove('w-80');
                sidebar.classList.add('w-18');
                document.querySelectorAll('.sidebar-label').forEach(label => {
                    label.classList.add('hidden');
                });
            }
        });
    });
</script>

<script src="{{ asset('js/fullscreen.js') }}"></script>
