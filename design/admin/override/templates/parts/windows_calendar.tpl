{* Details window. *}
{section show=ezpreference( 'admin_navigation_details' )}
    {include uri='design:details.tpl'}
{/section}

{* Translations window. *}
{section show=ezpreference( 'admin_navigation_translations' )}
    {include uri='design:translations.tpl'}
{/section}

{* Locations window. *}
{section show=ezpreference( 'admin_navigation_locations' )}
    {include uri='design:locations.tpl'}
{/section}

{* Relations window. *}
{section show=or( ezpreference( 'admin_navigation_relations' ),
                  and( is_set( $view_parameters.show_relations ), eq( $view_parameters.show_relations, 1 ) ) )}
    {include uri='design:relations.tpl'}
{/section}


        <div class="content-view-calendar">
            <div class="context-block">
                <div class="box-header">
                    <div class="box-tc">
                        <div class="box-ml">
                            <div class="box-mr">
                                <div class="box-tl">
                                    <div class="box-tr">
                                        <h2 class="context-title">
                                            Calendar
                                        </h2>
                                        <div class="header-subline"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-content">
                            <div class="context-toolbar">
                                <div class="block">
                                    <div class="left">
                                        <a href="#" class="bc-quickview-prev">&laquo; Prev Month</a>
                                        <a href="#" class="bc-quickview-next">Next Month &raquo;</a>
                                    </div>
                                    <div class="right">
                                    </div>
                                    <div class="break"></div>
                                </div>
                            </div><span id="bc_quickview_month" title="{currentdate()|datetime('custom','%n')}"></span><span id="bc_quickview_year" title="{currentdate()|datetime('custom','%Y')}"></span>
                            <div class="block bc-calendar-quickview" title="{ezini('BlendCalendarSettings','QuickViewClassAttributeId','content.ini')}|{$node.node_id}">
                                <div class="left">
                                <!-- 30 boxes view -->

                                </div>
                                <div class="right">
                                <!-- Individual day view -->
                                </div>
                                <div class="break"></div>
                            </div>
                            <div class="context-toolbar"></div>
                        </div>
                    </div>
                </div>
                <div class="controlbar">
                    <div class="box-bc">
                        <div class="box-ml">
                            <div class="box-mr">
                                <div class="box-tc">
                                    <div class="box-bl">
                                        <div class="box-br">
                                            <div class="block">
                                                <div class="left"></div>
                                                <div class="right"></div>
                                                <div class="break"></div>
                                            </div>
                                            <div class="block">
                                                <div class="left"></div>
                                                <div class="right"></div>
                                                <div class="break"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



{* Children window.*}
{section show=$node.object.content_class.is_container}
    {include uri='design:children.tpl'}
{section-else}
    {include uri='design:no_children.tpl'}
{/section}
