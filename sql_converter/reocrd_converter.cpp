#include <iostream>
#include <cstdio>
#include <cstring>
#include <cmath>
#include <vector>
#include <queue>
#include <set>
#include <map>
#include <string>
#include <algorithm>
#define MAXN 100010
#define MAXSIZE 200
#define MAXVERTEX 507
#define MAXEDGE 400010
#define ALPHASIZE 26
#define INF 999999999
#define DLEN 4
#define nil NULL
#define MP make_pair
using namespace std;
typedef long long ll;
typedef pair<int, int> pii;
const int mod = 1e9+7;

int main() {
    freopen("records.txt", "r", stdin);
    freopen("sql_records.sql", "w", stdout);

    string strid, date, time;
    while (cin >> strid >> date >> time) {
        cout << "insert into `record` (`strid`, `time`) values (\'" << strid << "\', \'" << date << ' ' << time << "\');" << endl;
    }
 
    fclose(stdin);
    fclose(stdout);

    return 0;
}
