from django.http import HttpResponse
from django.shortcuts import render

def homepage(request):
    return HttpResponse('homepage')

def register(request):
    #return HttpResponse('home')
    return render(request, 'signup.html')

def login(request):
    return HttpResponse('login')