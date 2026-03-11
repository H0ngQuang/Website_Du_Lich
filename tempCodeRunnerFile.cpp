
#include <bits/stdc++.h>
using namespace std;

void TC() {
	int n;
	cin >> n ;
	int a[1005];
	for(int i =1;i<=n;i++){
		cin >> a[i];
	}
	int i = n-1;
	while(i>0 && a[i] >= a[i+1]) i--;
	if (i == 0) {
        for (int j = 1; j <= n; ++j) {
            a[j] = j;
        }
    }
    else {
		int j =n ;
		while(a[i] > a[j]) j --;
		swap(a[i],a[j]);
		int l = i+1 , r= n;
		while(l<r){
			swap(a[l],a[r]);
			l++;
			r--;
		}
	}
	for (int i = 1; i <= n; ++i) {
        cout << a[i] << " ";
    }
}
int main() {
    
    int T = 1; cin >> T;
    while (T--) {
        TC();
        cout << "\n";
    }
    return 0;
}