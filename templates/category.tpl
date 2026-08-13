{extends file="layout.tpl"}

{block name="title"}
    {$category.name|escape}
    —
    {$appName|escape}
{/block}

{block name="content"}
    <h2>
        {$category.name|escape}
    </h2>
    {if $category.description}
        <p>
            {$category.description|escape}
        </p>
    {/if}
    <div>
        Сортировка:
        <a href="?sort=date">
            По дате
        </a>
        |
        <a href="?sort=views">
            По просмотрам
        </a>
    </div>
    <br>
    {if $articles}
        <div class="articles">
            {foreach $articles as $article}
                {include file="partials/article-card.tpl" article=$article}
            {/foreach}
        </div>
    {else}
        <p>
            В категории пока нет статей.
        </p>
    {/if}
    {if $totalPages > 1}
        {include file="partials/pagination.tpl" currentPage=$page totalPages=$totalPages sort=$sort}
    {/if}
{/block}