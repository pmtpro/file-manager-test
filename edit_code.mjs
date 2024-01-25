import { EditorView, basicSetup } from "codemirror"

import { EditorState, Compartment } from "@codemirror/state"
import {
    lineNumbers,
    highlightActiveLine,
    highlightSpecialChars,
    highlightActiveLineGutter,
    drawSelection
} from "@codemirror/view"

// theme
import { materialDark } from "cm6-theme-material-dark"

// lang
import { css } from "@codemirror/lang-css"
import { html } from "@codemirror/lang-html"
import { javascript } from "@codemirror/lang-javascript"
import { json } from "@codemirror/lang-json"
import { markdown } from "@codemirror/lang-markdown"
import { php } from "@codemirror/lang-php"
import { sass } from "@codemirror/lang-sass"
import { sql } from "@codemirror/lang-sql"
import { xml } from "@codemirror/lang-xml"
import { yaml } from "@codemirror/lang-yaml"
  
const languageConf = new Compartment()
const lineWrapConf = new Compartment()

const initialState = EditorState.create({
  doc: document.querySelector("#content").value,
  bracketMatching: true,
  highlightSelectionMatches: true,

  extensions: [
    drawSelection(),
    lineNumbers(),
    highlightActiveLineGutter(),
    highlightActiveLine(),
    highlightSpecialChars(),

    lineWrapConf.of([]),
    languageConf.of([]),

    materialDark
  ]    
})

const editor = new EditorView({
  state: initialState,
  parent: document.querySelector("#editor")
});


// doi ngon ngu
var codeLangElement = document.getElementById("code_lang");
codeLangElement.addEventListener("change", function () {
    var mode = codeLangElement.value;

    editor.dispatch({
        effects: languageConf.reconfigure(
            getLang(mode)
        )
    })
});

// ngon ngu mac dinh
editor.dispatch({
    effects: languageConf.reconfigure(
        getLang(codeLangElement.value)
    )
})

function getLang(mode) {
    let lang = [];
    
    switch (mode) {
        case 'javascript': lang = javascript(); break;
        case 'php': lang = php(); break;
    }
    
    return lang;
}

// che do wrap
var codeWrapElement = document.getElementById("code_wrap");
codeWrapElement.addEventListener("change", function () {
    let wrap = [];
    
    if (codeWrapElement.checked) {
        wrap = EditorView.lineWrapping;
    }
    
    editor.dispatch({
        effects: lineWrapConf.reconfigure(wrap)
    })
});

// xuat bien toan cau
window.editor = editor;