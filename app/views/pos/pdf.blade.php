<html>
    <head>
        <title>PO #{{ $po->id}}</title>
        <style type="text/css">
            body { color:#41584e; font-family: ConfluenceInstalledFont, Helvetica, Arial, sans-serif; }

            .checkbox, .checkboxChecked { 
                border: 1px solid black; 
                width: .65em; 
                height: .65em; 
                display: inline-block;
                margin-right: 4px;
            }
            .checkboxChecked {
                background:#000;
            }

            .lineItemTable { font-size:8pt; }
            .lineItemTable thead { background:#41584e;border:1px solid #41584e;color:#fff;}
            .lineItemTable thead td { border:1px solid #41584e; }
            .lineItemTable tbody td { border:1px solid #ccc; }

            .gray { background:#eee; }

            .textAlignCenter { text-align:center; }
        </style>
    </head>
    <body>
        <h4 style="margin-bottom:14px;text-align:center"><u><i>WRS GROUP, LTD.</i> PURCHASE ORDER</u></h4>
        <table width="100%" style="font-size:8pt;margin-bottom:15px">
            <tr>
                <td>
                    P.O. NUMBER: <b style="color:#cc0000;font-size:11pt;text-decoration:underline">{{ $po->id}}</b>
                </td>
                <td class="textAlignCenter">
                    SUBMITTED BY: {{ $po->submitter->display_name }}
                </td>
                <td class="textAlignCenter">
                   APPROVED BY: {{ $po->manager or 'N/A'}}
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-bottom:7px">
            <tbody>
                <tr>
                    <td>
                        <h4 style="margin:0"><i>WRS GROUP, LTD.</i></h4>
                        <hr style="margin:0"/>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td valign="top" style="width:200px">
                        @foreach( $divisions as $division )
                            <div style="font-size:8pt">
                                @if( in_array($division->id, $selectedDivisions))
                                    <span class="checkboxChecked"></span>
                                @else
                                    <span class="checkbox"></span>
                                @endif
                                <span style="position:relative;">{{ $division->name }}</span> 
                            </div>
                        @endforeach
                    </td>
                    <td style="width:150px">&nbsp;</td>
                    <td valign="top">
                        <div style="font-size:8pt;margin-bottom:5px">SHIP TO:</div>
                        @foreach( $locations as $location )
                            <div style="font-size:8pt">
                                @if( in_array($location->id, $selectedLocations))
                                    <span class="checkboxChecked"></span>
                                @else
                                    <span class="checkbox"></span>
                                @endif
                                <span style="position:relative;">{{ $location->address }}</span> 
                            </div>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table cellspacing="0" class="font-size:8pts" width="100%">
            <tr>
                <td style="border:1px solid #000;border-left-width:0;height:100px;width:50%;" valign="top">
                    <div style="padding:5px">
                        <div style="color:#888;font-size:7pt">VENDOR</div>
                        {{ $po->vendor }}
                    </div>
                </td>
                <td style="border:1px solid #000;border-right-width:0;height:100px;width:50%" valign="top">
                    <div style="padding:5px">
                        <div style="color:#888;font-size:7pt">SHIP TO: (IF DIFFERENT THAN ABOVE)</div>
                        {{ $po->ship_to }}
                    </div>
                </td>
            </tr>
        </table>

        <table border="0" cellspacing="0" class="lineItemTable" width="100%">
            <thead>
                <tr>
                    <td>ITEM / MFG. No. / DESCRIPTION</td>
                    <td class="textAlignCenter">DUE DATE</td>
                    <td class="textAlignCenter">QTY</td>
                    <td class="textAlignCenter">UM</td>
                    <td class="textAlignCenter">UNIT COST</td>
                    <td class="textAlignCenter">UM</td>
                    <td class="textAlignCenter">TAX</td>
                    <td class="textAlignCenter">TOTAL</td>
                </tr>
            </thead>
            <tbody>
            <?php $counter = 0;?>
            @foreach($items as $item)
                <?php $counter++;?>
                <tr class="{{ ($counter % 2 == 0) ? '#' : 'gray' }}">
                    <td>{{ $item->description }}</td>
                    <td class="textAlignCenter">{{ $item->due_date }}</td>
                    <td class="textAlignCenter">{{ $item->qty }}</td>
                    <td class="textAlignCenter">{{ $item->um }}</td>
                    <td class="textAlignCenter">{{ $item->uc }}</td>
                    <td class="textAlignCenter">{{ $item->uc_um }}</td>
                    <td class="textAlignCenter">{{ $item->tax }}</td>
                    <td class="textAlignCenter">{{ $item->total }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </body>
</html>