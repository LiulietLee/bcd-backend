#include <iostream>
#include <string>
using namespace std;

void escapeString(string &str) {
    string tstr = "";
    for (int i = 0; i < str.length(); i++) {
        if (str[i] != '\'') {
            tstr += str[i];
            if (str[i] == '\\') {
                tstr += "\\";
            }
        }
    }
    str = tstr;
}

int main() {
    freopen("records.txt", "r", stdin);
    freopen("sql_records.sql", "w", stdout);

    string strid, date, time;
    while (cin >> strid >> date >> time) {
        escapeString(strid);
        escapeString(date);
        escapeString(time);
        cout << "insert into `record` (`strid`, `time`) values (\'" << strid << "\', \'" << date << ' ' << time << "\');" << endl;
    }
 
    fclose(stdin);
    fclose(stdout);

    return 0;
}
