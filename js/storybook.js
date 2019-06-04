/**
 * @file
 * Plugin to handle storybook
 */
(function ($, Drupal) {
  Drupal.behaviors.storybook = {
    attach: function (context, settings) {
      const $context = $(context)
      const $items = $context.find('.storybook-navigation li')
      const $components = $context.find('.component-wrapper .component')

      $items.click(e => {
        const $el = $(e.target)
        const id = $el.data('target')
        const $component = $context.find(`.component-wrapper #${id}`)
        $components.removeClass('show')
        $component.addClass('show')
      })
    }
  }
})(jQuery, Drupal)
