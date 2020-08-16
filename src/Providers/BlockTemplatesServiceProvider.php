<?php

namespace TinyPixel\BlockTemplates\Providers;

use Illuminate\Support\Collection;
use Roots\Acorn\ServiceProvider;

/**
 * Block Templates Service Provider
 */
class BlockTemplatesServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->blocks = Collection::make();

        add_action('init', [$this, 'registerBlockTemplates'], 9);
    }

    /**
     * Boot application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->collectTemplateDirs()->map(function ($dir) {
            $this->collectTemplates($dir)->map(function ($view) use ($dir) {
                $this->blocks->push(basename($dir) . '/' . basename($view, '.blade.php'));
            });
        });
    }

    /**
     * Register block template
     *
     * @return void
     */
    public function registerBlockTemplates(): void
    {
        $this->blocks->each(function ($block) {
            register_block_type($block, [
                'render_callback' => function ($attributes, $content) use ($block) {
                    return $this->renderBlock($block, $attributes, $content);
                },
            ]);
        });
    }

    /**
     * Block renderer
     *
     * @param  string $block custom block template
     * @param  array  $attributes block attributes from gutenberg cb
     * @param  string $content block content from gutenberg cb
     * @return string rendered template content
     */
    protected function renderBlock(string $block, array $attributes, string $content): string
    {
        return $this->app['view']->make("blocks/{$block}")->with([
            'attr' => (object) $this->castRecursively($attributes),
            'content' => $this->hasContent($content) ? $content : null,
        ]);
    }

    /**
     * Get block template dirs
     *
     * @return \Illuminate\Support\Collection template directories
     */
    protected function collectTemplateDirs(): \Illuminate\Support\Collection
    {
        return Collection::make(glob(
            get_theme_file_path('/resources/views/blocks') . '/*'
        ));
    }

    /**
     * Collect templates
     *
     * @param string directory
     * @return \Illuminate\Support\Collection templates
     */
    protected function collectTemplates(string $dir): \Illuminate\Support\Collection
    {
        return Collection::make(glob("{$dir}/*"));
    }

    /**
     * Conditional check for existence of block content
     *
     * @param  string $content content from gutenberg render cb
     * @return bool true if content exists
     */
    protected function hasContent($content): bool
    {
        return !empty(wp_strip_all_tags($content, true)) || preg_match('/<(img|video|figure)/i', $content);
    }

    /**
     * Recursively cast mixed dataset to object
     *
     * @param  array $data item to cast
     * @return mixed cast item
     */
    protected function castRecursively($data)
    {
        return is_array($data) ? (object) array_map(__METHOD__, $data) : $data;
    }
}
