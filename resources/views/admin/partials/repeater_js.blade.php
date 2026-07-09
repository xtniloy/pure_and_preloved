{{-- Add/remove/renumber rows for .js-repeater blocks.
     A repeater needs: #id, data-next-index, .js-rows, <template class="js-row-template">
     (with __IDX__ placeholders), rows with .js-row / .js-row-number / .js-remove-row,
     and an add button with .js-add-row + data-repeater="<id>". --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function renumberRows(repeater) {
            repeater.querySelectorAll('.js-rows .js-row-number').forEach(function (el, index) {
                el.textContent = index + 1;
            });
        }

        document.querySelectorAll('.js-add-row').forEach(function (button) {
            button.addEventListener('click', function () {
                const repeater = document.getElementById(button.dataset.repeater);
                const rows = repeater.querySelector('.js-rows');
                const template = repeater.querySelector('.js-row-template');
                const index = parseInt(repeater.dataset.nextIndex || '0', 10);

                const wrapper = document.createElement('div');
                wrapper.innerHTML = template.innerHTML.replace(/__IDX__/g, index);
                repeater.dataset.nextIndex = index + 1;

                while (wrapper.firstElementChild) {
                    rows.appendChild(wrapper.firstElementChild);
                }

                renumberRows(repeater);
            });
        });

        document.addEventListener('click', function (event) {
            const removeBtn = event.target.closest('.js-remove-row');
            if (removeBtn) {
                const repeater = removeBtn.closest('.js-repeater');
                removeBtn.closest('.js-row').remove();
                if (repeater) renumberRows(repeater);
            }
        });

        document.querySelectorAll('.js-repeater').forEach(renumberRows);
    });
</script>
