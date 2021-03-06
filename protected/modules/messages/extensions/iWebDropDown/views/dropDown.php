
    <div class="category-select" id="<?= $id ?>">
        <? if (0 && isset($label)): ?>
            <div class="select-title"><?= $label ?></div>
        <? endif; ?>

        <div class="category-select-head popup-trigger">
            <div class="flash">
                <div class="glyphicon glyphicon-chevron-down"></div>
            </div>
            <?
            if (isset($title) && $title !== '') {
                foreach ($items as $item)
                    if ($item->id == $title)
                        $title = $item->title;
            } elseif (isset($label))
                $title = $label;
            else
                $title = 'همه';
            ?>
            <?= $title ?>
        </div>
        <div class="category-select-content popup">
            <ul data-id="<?= $name ?>" class="filter-parent">
                <? if (isset($allOption) && ($allOption === true)): ?>
                    <li data-value="all" class="filter-change">
                        <span>همه</span>
                    </li>
                <? endif; ?>

                <?php
                if (isset($list) && !is_null($list))
                    foreach ($list as $key => $value): ?>
                        <li data-value="<?= $key ?>" class="filter-change">
                            <span><?= $value ?></span>
                        </li>
                    <?php endforeach; ?>


                <?php
                if (isset($items))
                    foreach ($items as $item): ?>
                        <li data-value="<?= $item['id'] ?>" class="filter-change">
                            <span><?= $item['title'] ?></span>
                        </li>
                    <?php endforeach; ?>
            </ul>
        </div>
    </div>

<?
if (isset($model))
    echo CHtml::activeHiddenField($model, $name);
else
    echo CHtml::hiddenField($name);
?>