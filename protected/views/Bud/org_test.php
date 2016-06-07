<style>
    /*Now the CSS*/
    * {margin: 0; padding: 0;}

    .tree-org ul {
        padding-top: 20px; position: relative;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    .tree-org li {
        float: left; text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 5px 0 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    /*We will use ::before and ::after to draw the connectors*/

    .tree-org li::before, .tree-org li::after{
        content: '';
        position: absolute; top: 0; right: 50%;
        border-top: 1px solid #ccc;
        width: 50%; height: 20px;
    }
    .tree-org li::after{
        right: auto; left: 50%;
        border-left: 1px solid #ccc;
    }

    /*We need to remove left-right connectors from elements without 
    any siblings*/
    .tree-org li:only-child::after, .tree-org li:only-child::before {
        display: none;
    }

    /*Remove space from the top of single children*/
    .tree-org li:only-child{ padding-top: 0;}

    /*Remove left connector from first child and 
    right connector from last child*/
    .tree-org li:first-child::before, .tree-org li:last-child::after{
        border: 0 none;
    }
    /*Adding back the vertical connector to the last nodes*/
    .tree-org li:last-child::before{
        border-right: 1px solid #ccc;
        border-radius: 0 5px 0 0;
        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
    }
    .tree-org li:first-child::after{
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
    }

    /*Time to add downward connectors from parents*/
    .tree-org ul ul::before{
        content: '';
        position: absolute; top: 0; left: 50%;
        border-left: 1px solid #ccc;
        width: 0; height: 20px;
    }

    .tree-org li div.node{
        cursor:pointer;
        border: 1px solid #ccc;
        padding: 5px 10px;
        text-decoration: none;
        color: #666;
        font-family: arial, verdana, tahoma;
        font-size: 11px;
        display: inline-block;

        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    /*Time for some hover effects*/
    /*We will apply the hover effect the the lineage of the element also*/
    .tree-org li div.node:hover, .tree-org li div.node:hover+ul li a {
        background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
    }
    /*Connector styles on hover*/
    .tree-org li div.node:hover+ul li::after, 
    .tree-org li div.node:hover+ul li::before, 
    .tree-org li div.node:hover+ul::before, 
    .tree-org li div.node:hover+ul ul::before{
        border-color:  #94a0b4;
    }
    .tree-org > ul > li::before,
    .tree-org >ul > li:last-child::before,
    .tree-org > ul > li::after{
        border: none;
    }
    

    /*Thats all. I hope you enjoyed it.
    Thanks :)*/
</style>

<!--
We will create a family tree using just CSS(3)
The markup will be simple nested lists
-->
<div class="tree-org">
    <ul>
        <li>
            <div class="node">Parent</div>
            <ul>
                <li>
                    <div class="node">Child</div>
                    <ul>
                        <li>
                            <div class="node">Grand Child</div>
                        </li>
                    </ul>
                </li>
                <li>
                    <div class="node">Child</div>
                    <ul>
                        <li><div class="node">Grand Child</div></li>
                        <li>
                            <div class="node">Grand Child</div>
                            <ul>
                                <li>
                                    <div class="node">Great Grand Child</div>
                                </li>
                                <li>
                                    <div class="node">Great Grand Child</div>
                                </li>
                                <li>
                                    <div class="node">Great Grand Child</div>
                                </li>
                            </ul>
                        </li>
                        <li><div class="node">Grand Child</div></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><div class="node"><div class="dropdown-check-list">ฝ่ายบลา ๆ <br/>

                    <span class="anchor glyphicon glyphicon-plus"></span>
                    <div class="items">
                        <label><input type="checkbox" />แผนกทรัพยากรบุคคล </label>
                        <label><input type="checkbox" />Orange</label>
                        <label><input type="checkbox" />Grapes </label>
                        <label><input type="checkbox" />Berry </label>
                        <label><input type="checkbox" />Mango </label>
                        <label><input type="checkbox" />Banana </label>
                        <label><input type="checkbox" />Tomato</label>
                    </div>

                </div></div>
            <ul>
                <li><div class="node">c1</div></li>
                <li><div class="node">c2</div></li>
                <li><div class="node">c3</div></li>
            </ul>
        </li>
    </ul>
</div>
