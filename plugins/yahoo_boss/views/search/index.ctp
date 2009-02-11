<?php
echo $form->create('Search', array('url' => array('controller' => 'search', 'action' => 'results')));
echo $form->input('term', array('label' => false));
echo $form->end(__('Search', true));
?>

<?php if (!empty($term)) : ?>

  <p><?php __('You searched for '); echo $term; ?></p>

  <?php if (!empty($results)) : ?>

    <div class="pagination_info">
      <?php
      $paginator->options(array('url' => array_merge($this->passedArgs, array('term' => $term))));
      echo $paginator->counter(array(
        'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
      ));
      ?>
    </div>

    <ul>

      <?php foreach ($results as $result) : ?>

        <li>
          <h2><?php echo $html->link($result['title'], $result['clickurl'], null, null, false); ?></h2>
          <p><?php echo $result['abstract']; ?></p>
          <p><?php echo $html->link($result['url'], array('term' => $result['clickurl'])); ?></p>
          <?php if (isset($result['keyterms'])) : ?>
            <p style="display:none;">
              <?php foreach ($result['keyterms']['terms'] as $keyTerm) : ?>
                <?php echo $html->link($keyTerm, array('term' => urlencode($term.' '.$keyTerm))); ?>
              <?php endforeach; ?>
            </p>
          <?php endif; ?>
        </li>

      <?php endforeach; ?>

    </ul>

    <div class="pagination_links">
      <ul>
        <li><?php echo $paginator->prev(__('Previous', true), array(), null, array('class'=>'disabled', 'tag' => 'span'));?></li>
        <?php echo $paginator->numbers(array('tag' => 'li', 'separator' => ''));?>
        <li><?php echo $paginator->next(__('Next', true), array(), null, array('class'=>'disabled', 'tag' => 'span'));?></li>
      </ul>
    </div>

  <?php else : ?>

    <p><?php __('Sorry, no results.'); ?></p>

  <?php endif; ?>

  <?php if (!empty($spellingSuggestion)) : ?>
    <p><?php __('Did you mean '); echo $html->link($spellingSuggestion, array('term' => urlencode($spellingSuggestion))); ?></p>
  <?php endif; ?>

<?php endif; ?>