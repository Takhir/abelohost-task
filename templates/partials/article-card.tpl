<article class="article-card">
    {if $article.image}
        <img src="{$article.image|escape}" alt="{$article.title|escape}">
    {/if}
    <div class="article-card-content">
        <h3>
            <a href="/article/{$article.slug|escape}">
                {$article.title|escape}
            </a>
        </h3>
        {if $article.description}
            <p>
                {$article.description|escape}
            </p>
        {/if}
        <p class="meta">
            Просмотров: {$article.views}
        </p>
        <p class="meta">
            {$article.published_at|escape}
        </p>
    </div>
</article>