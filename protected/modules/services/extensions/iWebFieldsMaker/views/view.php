<table class="dynamic-fields">
    <thead>
    <tr>
        <th>عنوان</th>
        <th>مقدار</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?if($model->IsNewRecord):?>
        <tr>
            <td>
                <input name="Products[fields][1][title]" type="text" class="field-title">
            </td>
            <td>
                <input name="Products[fields][1][value]" type="text" class="field-value">
            </td>
            <td class="action-links">
                <a class="remove-link" href="#">
                    <i class="fa fa-trash-o"></i>
                </a>
            </td>
        </tr>
    <?else:
        $fields = json_decode($model->fields);
        if(!empty($fields))
            foreach ($fields as $key => $value)
            {
                echo
                    '<tr>
                        <td>
                            <input name="Products[fields]['.$key.'][title]" value="'.$value->title.'" type="text" class="field-title">
                                    </td>
                                    <td>
                                        <input name="Products[fields]['.$key.'][value]" value="'.$value->value.'" type="text" class="field-value">
                                    </td>
                                    <td class="action-links">
                                        <a class="remove-link" href="#">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>';
            }
    endif?>
    </tbody>
</table>
<a class="add-link" href="#">
    <i class="fa fa-plus"></i>
</a>