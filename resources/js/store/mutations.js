import Vue from 'vue'

/**
 * toggleEditor mutation
 * @param toggleEditor
 */
export const toggleEditor = (state, active = !state.editor.active) => {
  return Vue.set(state.editor, 'active', active)
}

/**
 * editLesson mutation
 * @param editLesson
 */
export const editLesson = (state, id) => {
  return Vue.set(state.editor, 'editing', id)
}

/**
 * editorDarkMode mutation
 * @param editorDarkMode
 */
export const editorDarkMode = (state, active) => {
  return Vue.set(state.editor, 'darkMode', active)
}
