'use strict';

let api = '../../vktest2/api/'

let getForm = document.getElementById('get')
let createForm = document.getElementById('create')
let responseElem = document.getElementById('response')
let sendEventElem = document.getElementById('send_event')
let controlsElem = document.getElementById('controls')

// Получает счетчики событий с параметрами
async function getEventsFunc() {
    let action = 'get'
    controlsLoading(true)
    let res = await fetch(api + action, {
        method: 'POST',
        body: new FormData(getForm)
    })
    let data = await res.json()
    updateResponseElem(data)
    updateSendEventElem(action)
    controlsLoading()
}

// Получает ВСЕ события
async function getAllEventsFunc() {
    let action = 'getAll'
    controlsLoading(true)
    let res = await fetch(api + action, {
        method: 'GET',
    })
    let data = await res.json()
    updateResponseElem(data)
    updateSendEventElem(action)
    controlsLoading()
}

// Создает новое событие по переданным параметрам
async function createEventFunc() {
    let action = 'create'
    controlsLoading(true)
    let res = await fetch(api + action, {
        method: 'POST',
        body: new FormData(createForm)
    })
    let data = await res.json()
    updateResponseElem(data)
    updateSendEventElem(action)
    controlsLoading()
}

// Обновляет блок с ответом api
function updateResponseElem(data) {
    responseElem.value = JSON.stringify(data, null, 4)
}

// Обновляет блок с отображением метода запроса
function updateSendEventElem(str) {
    sendEventElem.innerHTML = str
}

// Включает/выключает индикатор загрузки
function controlsLoading(on = false) {
    if (on) {
        controlsElem.classList.add('loading')
    } else {
        controlsElem.classList.remove('loading')
    }
}

