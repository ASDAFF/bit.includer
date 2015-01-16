arButtons['my_drop_list'] =
    ['BXEdList',
        {
            id: 'DropList',
            field_size: 75,
            title: '(itsfera.includer)',
            disableOnCodeView: true,
            values: window.arSections,

            /*OnSelectionChange: function (){
                this.SelectByVal(this.pMainObj.queryCommand('FontName'));
            },*/
            OnChange: function (selected){
                this.bNotFocus = true;
                this.pMainObj.insertHTML(selected['value'])
                window.bBitrixTabs = true;
            },
//text-overflow : ellipsis;
            OnDrawItem: function (item){return '<span style="white-space: nowrap; font-family:'+item['name']+';font-size: 10pt;">'+item['name']+'</span>';}
        }
    ];



if(!window.lightMode)
{
  //  oBXEditorUtils.appendButton('closed_content_1', arButtons['closed_content_1'], 'standart');
   // oBXEditorUtils.appendButton('closed_content_2', arButtons['closed_content_2'], 'standart');
    oBXEditorUtils.appendButton('DropList', arButtons['my_drop_list'], 'standart');
}
else
{
    for(var bxi=0, bxl=arGlobalToolbar.length; bxi<bxl; bxi++)
    {
        if (arGlobalToolbar[bxi +1] == 'line_end')
            break;
    }
    arGlobalToolbar = arGlobalToolbar.slice(0, bxi).concat([arButtons['my_drop_list']], arGlobalToolbar.slice(bxi + 1));
}
