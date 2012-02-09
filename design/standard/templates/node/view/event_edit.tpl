{$node.name}{*
           *}  <a href={concat( 'content/edit/', $node.object.id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $node.object.name ) )|wash}" /></a>
