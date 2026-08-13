{extends file="layout.tpl"}

{block name="title"}
    {$appName|escape}
{/block}

{block name="content"}
    {if !$categories}
        <p>
            Пока нет категорий со статьями.
        </p>
    {/if}
    {foreach $categories as $category}
        <section class="category">
            <h2>
                {$category.name|escape}
            </h2>
            {if $category.description}
                <p>
                    {$category.description|escape}
                </p>
            {/if}
            <div class="articles">
                {foreach $category.articles as $article}
                    {include file="partials/article-card.tpl" article=$article}
                {/foreach}
            </div>
            <p>
                <a href="/category/{$category.slug|escape}">
                    Все статьи →
                </a>
            </p>
        </section>
    {/foreach}
{/block}