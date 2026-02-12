<?php
/**
 * Breadcrumb Navigation Component
 * SEO-optimized breadcrumbs for improved navigation and search engine understanding
 */

if (!isset($breadcrumbs) || !is_array($breadcrumbs)) {
    return;
}
?>

<nav class="breadcrumb-nav" aria-label="Breadcrumb">
    <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
            <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <?php if (isset($crumb['url']) && $crumb['url']): ?>
                    <a href="<?php echo e($crumb['url']); ?>" itemprop="item">
                        <span itemprop="name"><?php echo e($crumb['name']); ?></span>
                    </a>
                <?php else: ?>
                    <span itemprop="name"><?php echo e($crumb['name']); ?></span>
                <?php endif; ?>
                <meta itemprop="position" content="<?php echo ($index + 1); ?>">
            </li>
        <?php endforeach; ?>
    </ol>
</nav>

<style>
.breadcrumb-nav {
    margin: 1rem 0 1.5rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.breadcrumb-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
    font-size: 0.875rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    color: var(--text-secondary);
}

.breadcrumb-item:not(:last-child)::after {
    content: '›';
    margin-left: 0.5rem;
    color: var(--text-light);
    font-weight: 600;
}

.breadcrumb-item a {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.2s ease;
}

.breadcrumb-item a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.breadcrumb-item:last-child {
    color: var(--text-primary);
    font-weight: 500;
}

@media (max-width: 768px) {
    .breadcrumb-list {
        font-size: 0.8125rem;
    }
    
    .breadcrumb-nav {
        margin: 0.75rem 0 1rem;
        padding: 0.5rem 0;
    }
}
</style>
