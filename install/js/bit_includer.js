arButtons['bit_includer_list'] = ['BXButton',
    {
        id: 'bit_includer_list',
        codeEditorMode: false,
        src: '/bitrix/images/bit.includer/bit_list.png',
        name: 'Добавить список [list][/list]',
        handler: function()
        {
            this.bNotFocus = true;
            this.pMainObj.insertHTML('[list][/list]');
            window.bBitrixTabs = true;
        }
    }
];
arButtons['bit_includer_detail'] = ['BXButton',
    {
        id: 'bit_includer_detail',
        codeEditorMode: false,
        src: '/bitrix/images/bit.includer/bit_detail.png',
        name: 'Добавить элемент [detail][/detail]',
        handler: function()
        {
            this.bNotFocus = true;
            this.pMainObj.insertHTML('[detail][/detail]');
            window.bBitrixTabs = true;
        }
    }
];

arButtons['bit_includer_drop'] =
    ['BXEdList',
        {
            id: 'bit_includer_drop',
            field_size: 75,
            title: '(bit.includer)',
            disableOnCodeView: true,
            values: window.arSections,

            OnChange: function (selected){
                this.bNotFocus = true;
                this.pMainObj.insertHTML(selected['value'])
                window.bBitrixTabs = true;
            },
            OnDrawItem: function (item){return '<span style="white-space: nowrap; font-family:'+item['name']+';font-size: 10pt;">'+item['name']+'</span>';}
        }
    ];



if(!window.lightMode)
{
    oBXEditorUtils.appendButton('bit_includer_list', arButtons['bit_includer_list'], 'standart');
    oBXEditorUtils.appendButton('bit_includer_detail', arButtons['bit_includer_detail'], 'standart');
    oBXEditorUtils.appendButton('bit_includer_drop', arButtons['bit_includer_drop'], 'standart');
}
else
{
    for(var bxi=0, bxl=arGlobalToolbar.length; bxi<bxl; bxi++)
    {
        if (arGlobalToolbar[bxi +1] == 'line_end')
            break;
    }
    arAddedButtons = [arButtons['bit_includer_list'],arButtons['bit_includer_detail'],arButtons['bit_includer_drop']];
    arGlobalToolbar = arGlobalToolbar.slice(0, bxi).concat(arAddedButtons, arGlobalToolbar.slice(bxi + 1));
}
