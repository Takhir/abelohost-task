{extends file="layout.tpl"}

{block name="title"}
    {$article.title|escape}
    —
    {$appName|escape}
{/block}

{block name="content"}
    <article>
        <h2>
            {$article.title|escape}
        </h2>
        {if $article.image}
            <img src="{$article.image|escape}" alt="{$article.title|escape}" style="max-width: 100%;">
        {/if}
        {if $categories}
            <p>
                Категории:
                {foreach $categories as $category}
                    <a href="/category/{$category.slug|escape}">
                        {$category.name|escape}
                    </a>
                    {if !$category@last}
                        ,
                    {/if}
                {/foreach}
            </p>
        {/if}
        <p class="meta">
            Опубликовано: {$article.published_at|escape}
        </p>
        <p class="meta">
            Просмотров: {$article.views}
        </p>
        {if $article.description}
            <p>
                <strong>
                    {$article.description|escape}
                </strong>
            </p>
        {/if}
        <div>
            {$article.content|escape|nl2br}
        </div>
    </article>
    {if $similarArticles}
        <section>
            <h2>
                Похожие статьи
            </h2>
            <div class="articles">
                {foreach $similarArticles as $article}
                    {include file="partials/article-card.tpl" article=$article}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}