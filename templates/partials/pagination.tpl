<nav class="pagination">
    {if $currentPage > 1}
        <a href="?page={$currentPage - 1}&sort={$sort|escape}">
            ← Назад
        </a>
    {/if}
    {for $i=1 to $totalPages}
        {if $i == $currentPage}
            <span class="active">
                {$i}
            </span>
        {else}
            <a href="?page={$i}&sort={$sort|escape}">
                {$i}
            </a>
        {/if}
    {/for}
    {if $currentPage < $totalPages}
        <a href="?page={$currentPage + 1}&sort={$sort|escape}">
            Вперёд →
        </a>
    {/if}
</nav>