{def $questions = fetch('content','list',hash(
'parent_node_id',$node.data_map.redirect_url.content.main_node.node_id,
'sort_by',$node.sort_array,
'class_filter_array',ezini('Classes','QuestionClasses','smileform.ini')
))}
{def $atts = fetch('content','list',hash(
'parent_node_id',$node.parent_node_id,
'sort_by',$node.sort_array,
'class_filter_array',ezini('Classes','QuestionClasses','smileform.ini')
))}

{foreach $atts as $att}
    {foreach $questions as $question}
            {if $question.name|downcase()|begins_with($att.name|downcase())}
                    <div class="hidden">
                        {if $att.data_map.contentclass_attribute_identifier|eq('gender')}
                            {node_view_gui content_node=$question view='form' default_value='madame'}
                        {else}
                            {node_view_gui content_node=$question view='form' default_value=''}
                        {/if}
                    </div>
            {/if}
        {/foreach}
{/foreach}