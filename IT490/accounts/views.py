from django.shortcuts import render, redirect
from django.http import HttpResponse
from accounts.forms import RegistrationForm

# Create your views here.
def signup_view(request):
    if request.method == 'POST':
        form = RegistrationForm(request.POST)
        if form.is_valid():
            form.save()
            #login
            return HttpResponse('signed up')

    else:
        form = RegistrationForm()
    return render(request, 'accounts/signup.html', {'form': form})

def login_view(request):
    if request.method == 'POST':
        form = AuthenticationForm(data=request.POST)
        if form.is_valid():
            return HttpResponse('login')
    else:
        form = AuthenticationForm()
    return render(request, 'accounts/login.html', {'form': form})



