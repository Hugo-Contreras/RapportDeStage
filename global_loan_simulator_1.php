<div class="content">
    <select id="loan-choice">
        {foreach $node.data_map.loan_choice.content.rows.sequential as $key => $row}

            <option value="choice-{$key}" id="choice-{$key}">{$row.columns.0|wash()}</option>

        {/foreach}
    </select>
    {foreach $node.data_map.corresponding_simulator.content.relation_list as $key => $row}

        {def $url_alias_node=fetch( 'content', 'node', hash( 'node_id', $row.node_id ) )}
        <input id="simu-{$key}" type="hidden" value="{concat('/layout/set/nolayout/', $url_alias_node.url)}" />

    {/foreach}
    <div id="simu-content">

    </div>
</div>