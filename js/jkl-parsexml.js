if ( typeof(JKL) == 'undefined' ) JKL = function() {};

JKL.ParseXML = function ( url, query, method ) {
    // debug.print( "new JKL.ParseXML( '"+url+"', '"+query+"', '"+method+"' );" );
    this.http = new JKL.ParseXML.HTTP( url, query, method, false );
    return this;
};

JKL.ParseXML.VERSION = "0.22";
JKL.ParseXML.MIME_TYPE_XML  = "text/xml";
JKL.ParseXML.MAP_NODETYPE = [
    "",
    "ELEMENT_NODE",                 // 1
    "ATTRIBUTE_NODE",               // 2
    "TEXT_NODE",                    // 3
    "CDATA_SECTION_NODE",           // 4
    "ENTITY_REFERENCE_NODE",        // 5
    "ENTITY_NODE",                  // 6
    "PROCESSING_INSTRUCTION_NODE",  // 7
    "COMMENT_NODE",                 // 8
    "DOCUMENT_NODE",                // 9
    "DOCUMENT_TYPE_NODE",           // 10
    "DOCUMENT_FRAGMENT_NODE",       // 11
    "NOTATION_NODE"                 // 12
];

JKL.ParseXML.prototype.async = function ( func, args ) {
    this.callback_func = func;      // callback function
    this.callback_arg  = args;      // first argument
};

JKL.ParseXML.prototype.onerror = function ( func, args ) {
    this.onerror_func = func;       // callback function
};

JKL.ParseXML.prototype.parse = function () {
    if ( ! this.http ) return;

    // set onerror call back 
    if ( this.onerror_func ) {
        this.http.onerror( this.onerror_func );
    }

    if ( this.callback_func ) {                             // async mode
        var copy = this;
        var proc = function() {
            if ( ! copy.http ) return;
            var data = copy.parseResponse();
            copy.callback_func( data, copy.callback_arg );  // call back
        };
        this.http.async( proc );
    }

    this.http.load();

    if ( ! this.callback_func ) {                           // sync mode
        var data = this.parseResponse();
        return data;
    }
};

JKL.ParseXML.prototype.setOutputArrayAll = function () {
    this.setOutputArray( true );
}
JKL.ParseXML.prototype.setOutputArrayAuto = function () {
    this.setOutputArray( null );
}
JKL.ParseXML.prototype.setOutputArrayNever = function () {
    this.setOutputArray( false );
}
JKL.ParseXML.prototype.setOutputArrayElements = function ( list ) {
    this.setOutputArray( list );
}
JKL.ParseXML.prototype.setOutputArray = function ( mode ) {
    if ( typeof(mode) == "string" ) {
        mode = [ mode ];                // string into array
    }
    if ( mode && typeof(mode) == "object" ) {
        if ( mode.length < 0 ) {
            mode = false;               // false when array == [] 
        } else {
            var hash = {};
            for( var i=0; i<mode.length; i++ ) {
                hash[mode[i]] = true;
            }
            mode = hash;                // array into hashed array
            if ( mode["*"] ) {
                mode = true;            // true when includes "*"
            }
        } 
    } 
    this.usearray = mode;
}

JKL.ParseXML.prototype.parseResponse = function () {
    var root = this.http.documentElement();
    var data = this.parseDocument( root );
    return data;
}

JKL.ParseXML.prototype.parseDocument = function ( root ) {
    if ( ! root ) return;

    var ret = this.parseElement( root );            // parse root node

    if ( this.usearray == true ) {                  // always into array
        ret = [ ret ];
    } else if ( this.usearray == false ) {          // always into scalar
        //
    } else if ( this.usearray == null ) {           // automatic
        //
    } else if ( this.usearray[root.nodeName] ) {    // specified tag
        ret = [ ret ];
    }

    var json = {};
    json[root.nodeName] = ret;                      // root nodeName
    return json;
};

JKL.ParseXML.prototype.parseElement = function ( elem ) {

    if ( elem.nodeType == 7 ) {
        return;
    }

    if ( elem.nodeType == 3 || elem.nodeType == 4 ) {
        var bool = elem.nodeValue.match( /[^\x00-\x20]/ ); // for Safari
        if ( bool == null ) return;     // ignore white spaces
        return elem.nodeValue;
    }

    var retval;
    var cnt = {};

    if ( elem.attributes && elem.attributes.length ) {
        retval = {};
        for ( var i=0; i<elem.attributes.length; i++ ) {
            var key = elem.attributes[i].nodeName;
            if ( typeof(key) != "string" ) continue;
            var val = elem.attributes[i].nodeValue;
            if ( ! val ) continue;
            if ( typeof(cnt[key]) == "undefined" ) cnt[key] = 0;
            cnt[key] ++;
            this.addNode( retval, key, cnt[key], val );
        }
    }

    if ( elem.childNodes && elem.childNodes.length ) {
        var textonly = true;
        if ( retval ) textonly = false;        // some attributes exists
        for ( var i=0; i<elem.childNodes.length && textonly; i++ ) {
            var ntype = elem.childNodes[i].nodeType;
            if ( ntype == 3 || ntype == 4 ) continue;
            textonly = false;
        }
        if ( textonly ) {
            if ( ! retval ) retval = "";
            for ( var i=0; i<elem.childNodes.length; i++ ) {
                retval += elem.childNodes[i].nodeValue;
            }
        } else {
            if ( ! retval ) retval = {};
            for ( var i=0; i<elem.childNodes.length; i++ ) {
                var key = elem.childNodes[i].nodeName;
                if ( typeof(key) != "string" ) continue;
                var val = this.parseElement( elem.childNodes[i] );
                if ( ! val ) continue;
                if ( typeof(cnt[key]) == "undefined" ) cnt[key] = 0;
                cnt[key] ++;
                this.addNode( retval, key, cnt[key], val );
            }
        }
    }
    return retval;
};

JKL.ParseXML.prototype.addNode = function ( hash, key, cnts, val ) {
    if ( this.usearray == true ) {              // into array
        if ( cnts == 1 ) hash[key] = [];
        hash[key][hash[key].length] = val;      // push
    } else if ( this.usearray == false ) {      // into scalar
        if ( cnts == 1 ) hash[key] = val;       // only 1st sibling
    } else if ( this.usearray == null ) {
        if ( cnts == 1 ) {                      // 1st sibling
            hash[key] = val;
        } else if ( cnts == 2 ) {               // 2nd sibling
            hash[key] = [ hash[key], val ];
        } else {                                // 3rd sibling and more
            hash[key][hash[key].length] = val;
        }
    } else if ( this.usearray[key] ) {
        if ( cnts == 1 ) hash[key] = [];
        hash[key][hash[key].length] = val;      // push
    } else {
        if ( cnts == 1 ) hash[key] = val;       // only 1st sibling
    }
};

JKL.ParseXML.Text = function ( url, query, method ) {
    this.http = new JKL.ParseXML.HTTP( url, query, method, true );
    return this;
};

JKL.ParseXML.Text.prototype.parse = JKL.ParseXML.prototype.parse;
JKL.ParseXML.Text.prototype.async = JKL.ParseXML.prototype.async;
JKL.ParseXML.Text.prototype.onerror = JKL.ParseXML.prototype.onerror;

JKL.ParseXML.Text.prototype.parseResponse = function () {
    var data = this.http.responseText();
    return data;
}

JKL.ParseXML.JSON = function ( url, query, method ) {
    this.http = new JKL.ParseXML.HTTP( url, query, method, true );
    return this;
};

JKL.ParseXML.JSON.prototype.parse = JKL.ParseXML.prototype.parse;
JKL.ParseXML.JSON.prototype.async = JKL.ParseXML.prototype.async;
JKL.ParseXML.JSON.prototype.onerror = JKL.ParseXML.prototype.onerror;

JKL.ParseXML.JSON.prototype.parseResponse = function () {
    var text = this.http.responseText();
    if ( typeof(text) == 'undefined' ) return;
    if ( ! text.length ) return;
    var data = eval( "("+text+")" );
    return data;
}

JKL.ParseXML.DOM = function ( url, query, method ) {
    this.http = new JKL.ParseXML.HTTP( url, query, method, false );
    return this;
};

JKL.ParseXML.DOM.prototype.parse = JKL.ParseXML.prototype.parse;
JKL.ParseXML.DOM.prototype.async = JKL.ParseXML.prototype.async;
JKL.ParseXML.DOM.prototype.onerror = JKL.ParseXML.prototype.onerror;

JKL.ParseXML.DOM.prototype.parseResponse = function () {
    var data = this.http.documentElement();
    return data;
}

JKL.ParseXML.CSV = function ( url, query, method ) {
    this.http = new JKL.ParseXML.HTTP( url, query, method, true );
    return this;
};

JKL.ParseXML.CSV.prototype.parse = JKL.ParseXML.prototype.parse;
JKL.ParseXML.CSV.prototype.async = JKL.ParseXML.prototype.async;
JKL.ParseXML.CSV.prototype.onerror = JKL.ParseXML.prototype.onerror;

JKL.ParseXML.CSV.prototype.parseResponse = function () {
    var text = this.http.responseText();
    var data = this.parseCSV( text );
    return data;
}

JKL.ParseXML.CSV.prototype.parseCSV = function ( text ) {
    text = text.replace( /\r\n?/g, "\n" );              // new line character
    var pos = 0;
    var len = text.length;
    var table = [];
    while( pos<len ) {
        var line = [];
        while( pos<len ) {
            if ( text.charAt(pos) == '"' ) {            // "..." quoted column
                var nextquote = text.indexOf( '"', pos+1 );
                while ( nextquote<len && nextquote > -1 ) {
                    if ( text.charAt(nextquote+1) != '"' ) {
                        break;                          // end of column
                    }
                    nextquote = text.indexOf( '"', nextquote+2 );
                }
                if ( nextquote < 0 ) {
                    // unclosed quote
                } else if ( text.charAt(nextquote+1) == "," ) { // end of column
                    var quoted = text.substr( pos+1, nextquote-pos-1 );
                    quoted = quoted.replace(/""/g,'"');
                    line[line.length] = quoted;
                    pos = nextquote+2;
                    continue;
                } else if ( text.charAt(nextquote+1) == "\n" || // end of line
                            len==nextquote+1 ) {                // end of file
                    var quoted = text.substr( pos+1, nextquote-pos-1 );
                    quoted = quoted.replace(/""/g,'"');
                    line[line.length] = quoted;
                    pos = nextquote+2;
                    break;
                } else {
                    // invalid column
                }
            }
            var nextcomma = text.indexOf( ",", pos );
            var nextnline = text.indexOf( "\n", pos );
            if ( nextnline < 0 ) nextnline = len;
            if ( nextcomma > -1 && nextcomma < nextnline ) {
                line[line.length] = text.substr( pos, nextcomma-pos );
                pos = nextcomma+1;
            } else {                                    // end of line
                line[line.length] = text.substr( pos, nextnline-pos );
                pos = nextnline+1;
                break;
            }
        }
        if ( line.length >= 0 ) {
            table[table.length] = line;                 // push line
        }
    }
    if ( table.length < 0 ) return;                     // null data
    return table;
};

JKL.ParseXML.CSVmap = function ( url, query, method ) {
    this.http = new JKL.ParseXML.HTTP( url, query, method, true );
    return this;
};

JKL.ParseXML.CSVmap.prototype.parse = JKL.ParseXML.prototype.parse;
JKL.ParseXML.CSVmap.prototype.async = JKL.ParseXML.prototype.async;
JKL.ParseXML.CSVmap.prototype.onerror = JKL.ParseXML.prototype.onerror;
JKL.ParseXML.CSVmap.prototype.parseCSV = JKL.ParseXML.CSV.prototype.parseCSV;

JKL.ParseXML.CSVmap.prototype.parseResponse = function () {
    var text = this.http.responseText();
    var source = this.parseCSV( text );
    if ( ! source ) return;
    if ( source.length < 0 ) return;

    var title = source.shift();                 // first line as title
    var data = [];
    for( var i=0; i<source.length; i++ ) {
        var hash = {};
        for( var j=0; j<title.length && j<source[i].length; j++ ) {
            hash[title[j]] = source[i][j];      // array to map
        }
        data[data.length] = hash;               // push line
    }
    return data;
}

JKL.ParseXML.LoadVars = function ( url, query, method ) {
    this.http = new JKL.ParseXML.HTTP( url, query, method, true );
    return this;
};

JKL.ParseXML.LoadVars.prototype.parse = JKL.ParseXML.prototype.parse;
JKL.ParseXML.LoadVars.prototype.async = JKL.ParseXML.prototype.async;
JKL.ParseXML.LoadVars.prototype.onerror = JKL.ParseXML.prototype.onerror;

JKL.ParseXML.LoadVars.prototype.parseResponse = function () {
    var text = this.http.responseText();
    text = text.replace( /\r\n?/g, "\n" );              // new line character
    var hash = {};
    var list = text.split( "&" );
    for( var i=0; i<list.length; i++ ) {
        var eq = list[i].indexOf( "=" );
        if ( eq > -1 ) {
            var key = decodeURIComponent(list[i].substr(0,eq).replace("+","%20"));
            var val = decodeURIComponent(list[i].substr(eq+1).replace("+","%20"));
            hash[key] = val;
        } else {
            hash[list[i]] = "";
        }
    }
    return hash;
};

JKL.ParseXML.HTTP = function( url, query, method, textmode ) {
    this.url = url;
    if ( typeof(query) == "string" ) {
        this.query = query;
    } else {
        this.query = "";
    }
    if ( method ) {
        this.method = method;
    } else if ( typeof(query) == "string" ) {
        this.method = "POST";
    } else {
        this.method = "GET";
    }
    this.textmode = textmode ? true : false;
    this.req = null;
    this.xmldom_flag = false;
    this.onerror_func  = null;
    this.callback_func = null;
    this.already_done = null;
    return this;
};

JKL.ParseXML.HTTP.REQUEST_TYPE  = "application/x-www-form-urlencoded";
JKL.ParseXML.HTTP.ACTIVEX_XMLDOM  = "Microsoft.XMLDOM";  // Msxml2.DOMDocument.5.0
JKL.ParseXML.HTTP.ACTIVEX_XMLHTTP = "Microsoft.XMLHTTP"; // Msxml2.XMLHTTP.3.0
JKL.ParseXML.HTTP.EPOCH_TIMESTAMP = "Thu, 01 Jun 1970 00:00:00 GMT"

JKL.ParseXML.HTTP.prototype.onerror = JKL.ParseXML.prototype.onerror;
JKL.ParseXML.HTTP.prototype.async = function( func ) {
    this.async_func = func;
}

JKL.ParseXML.HTTP.prototype.load = function() {
    if ( window.ActiveXObject ) {                           // IE5.5,6,7
        var activex = JKL.ParseXML.HTTP.ACTIVEX_XMLHTTP;    // IXMLHttpRequest
        if ( this.method == "GET" && ! this.textmode ) {
            activex = JKL.ParseXML.HTTP.ACTIVEX_XMLDOM;     // IXMLDOMElement
        }
        this.req = new ActiveXObject( activex );
    } else if ( window.XMLHttpRequest ) {                   // Firefox, Opera, iCab
        this.req = new XMLHttpRequest();
    }

    var async_flag = this.async_func ? true : false;

    if ( typeof(this.req.send) != "undefined" ) {
        this.req.open( this.method, this.url, async_flag );
    }

    if ( typeof(this.req.setRequestHeader) != "undefined" ) {
        this.req.setRequestHeader( "Content-Type", JKL.ParseXML.HTTP.REQUEST_TYPE );
    }

    if ( typeof(this.req.overrideMimeType) != "undefined" && ! this.textmode ) {
        this.req.overrideMimeType( JKL.ParseXML.MIME_TYPE_XML );
    }

    if ( async_flag ) {
        var copy = this;
        copy.already_done = false;                  // not parsed yet
        var check_func = function () {
            if ( copy.req.readyState != 4 ) return;
            var succeed = copy.checkResponse();
            if ( ! succeed ) return;                // failed
            if ( copy.already_done ) return;        // parse only once
            copy.already_done = true;               // already parsed
            copy.async_func();                      // call back async
        };
        this.req.onreadystatechange = check_func;
    }

    if ( typeof(this.req.send) != "undefined" ) {
        this.req.send( this.query );                        // XMLHTTPRequest
    } else if ( typeof(this.req.load) != "undefined" ) {
        this.req.async = async_flag;
        this.req.load( this.url );                          // IXMLDOMElement
    }

    if ( async_flag ) return;

    var succeed = this.checkResponse();
}

JKL.ParseXML.HTTP.prototype.checkResponse = function() {
    if ( this.req.parseError && this.req.parseError.errorCode != 0 ) {
        if ( this.onerror_func ) this.onerror_func( this.req.parseError.reason );
        return false;                       // failed
    }

    if ( this.req.status-0 > 0 &&
         this.req.status != 200 &&          // OK
         this.req.status != 206 &&          // Partial Content
         this.req.status != 304 ) {         // Not Modified
        if ( this.onerror_func ) this.onerror_func( this.req.status );
        return false;                       // failed
    }

    return true;                            // succeed
}

JKL.ParseXML.HTTP.prototype.documentElement = function() {
    if ( ! this.req ) return;
    if ( this.req.responseXML ) {
        return this.req.responseXML.documentElement;    // XMLHTTPRequest
    } else {
        return this.req.documentElement;                // IXMLDOMDocument
    }
}


JKL.ParseXML.HTTP.prototype.responseText = function() {
    if ( ! this.req ) return;

    if ( navigator.appVersion.match( "KHTML" ) ) {
        var esc = escape( this.req.responseText );
        if ( ! esc.match("%u") && esc.match("%") ) {
            return decodeURIComponent(esc);
        }
    }

    return this.req.responseText;
}
